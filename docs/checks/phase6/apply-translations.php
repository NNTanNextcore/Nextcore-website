<?php
/** Explicit local-only reviewed dictionary updates; default dry-run. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
define('DISABLE_WP_CRON', true);
$_SERVER['HTTP_HOST']='localhost'; $_SERVER['REQUEST_URI']='/'; $_SERVER['REQUEST_METHOD']='GET';
$GLOBALS['wp_filter']['query'][1][] = array('function'=>function($sql){
    if(preg_match('/^\s*(SELECT|SHOW|DESCRIBE|EXPLAIN)\b/i',$sql)){return $sql;}
    if(!empty($GLOBALS['nc6_translation_write']) && preg_match('/^(INSERT(?: IGNORE)? INTO|UPDATE)\s+\x60?[a-z0-9_]+trp_/i',trim($sql))){return $sql;}
    return 'SELECT NULL WHERE 1=0';
},'accepted_args'=>1);
ob_start();require dirname(__DIR__,3).'/wp-load.php';
if(!in_array(DB_HOST,array('localhost','127.0.0.1','::1'),true)||get_stylesheet()!=='nextcore-theme'){throw new RuntimeException('Active local Nextcore required.');}
$apply=in_array('--apply-local',$argv,true);
$query=TRP_Translate_Press::get_trp_instance()->get_component('query');
$gettext=$query->get_query_component('gettext_insert_update');
$language='en_US';
$table=$query->get_table_name($language);$gettext_table=$query->get_gettext_table_name($language);
$items=json_decode(file_get_contents(__DIR__.'/approved-translations.json'),true);
$old_review=json_decode(file_get_contents(dirname(__DIR__).'/phase5/translation-matches.json'),true);
$report=array('mode'=>$apply?'apply-local':'dry-run','writes'=>array(),'skipped'=>array(),'conflicts'=>array());
$counts=function()use($wpdb){$out=array();foreach($wpdb->get_col("SHOW TABLES LIKE '{$wpdb->prefix}trp_%'") as $t){$out[$t]=(int)$wpdb->get_var("SELECT COUNT(*) FROM $t");}return $out;};
$report['before_counts']=$counts();
$html=file_get_contents('http://localhost/');
$block=function($pattern)use($html){if(!preg_match($pattern,$html,$m)){throw new RuntimeException('Missing translation block.');}return trp_full_trim($m[1]);};
$blocks=array(
    2=>array($block('~<h1[^>]*id="hero-title"[^>]*>(.*?)</h1>~s'),'<span class="company-line"><span class="company-name">Nextcore</span> Software</span> <span class="company-line">Joint Stock Company</span>'),
    12=>array($block('~<div class="company-description translation-block"[^>]*>(.*?)</div>~s'),$items[11]['translation']),
    30=>array($block('~<h2 id="technology-title"[^>]*>(.*?)</h2>~s'),nl2br($items[29]['translation'])),
    50=>array($block('~<p class="translation-block"[^>]*>(.*?)</p>~s'),nl2br($items[49]['translation'])),
);
$gettext_sources=array();
$iterator=new RecursiveIteratorIterator(new RecursiveDirectoryIterator(get_theme_file_path(),FilesystemIterator::SKIP_DOTS));
foreach($iterator as $file){
    if($file->getExtension()!=='php'){continue;}
    $tokens=array_values(array_filter(token_get_all(file_get_contents($file->getPathname())),function($t){return !is_array($t)||!in_array($t[0],array(T_WHITESPACE,T_COMMENT,T_DOC_COMMENT),true);}));
    foreach($tokens as $i=>$t){
        if(!is_array($t)||$t[0]!==T_STRING||!in_array($t[1],array('__','_e','esc_html__','esc_html_e','esc_attr__','esc_attr_e'),true)){continue;}
        $literal=$tokens[$i+2]??null;
        if(is_array($literal)&&$literal[0]===T_CONSTANT_ENCAPSED_STRING){
            $value=substr($literal[1],1,-1);$value=str_replace(array("\\\\","\\'"),array("\\","'"),$value);$gettext_sources[$value]=true;
        }
    }
}
foreach($items as $entry){
    if($entry['id']===3){$report['skipped'][]=array('id'=>3,'reason'=>'Hero prefix belongs to the single full H1 block; no fragment translation.');continue;}
    $source=trp_full_trim($entry['source']);$translation=$entry['translation'];$block_type=0;
    if(isset($blocks[$entry['id']])){list($source,$translation)=$blocks[$entry['id']];$block_type=1;}
    if(!$source){continue;}
    $known=array();
    foreach($old_review[$entry['id']-1]['existing']??array() as $match){$known[]=$match['translated'];}
    $rows=$wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE BINARY original=%s",$source),ARRAY_A);
    if(!$rows&&$source===$translation&&!isset($gettext_sources[$source])){$report['skipped'][]=array('id'=>$entry['id'],'reason'=>'Unchanged proper name or existing English source.');continue;}
    $conflict=false;
    foreach($rows as $row){if($row['translated']!==''&&$row['translated']!==null&&$row['translated']!==$translation&&!in_array($row['translated'],$known,true)){$report['conflicts'][]=array('source'=>$source,'old'=>$row['translated'],'proposed'=>$translation,'table'=>$table);$conflict=true;}}
    if(!$conflict){
        if(!$rows&&$apply){$GLOBALS['nc6_translation_write']=true;$query->insert_strings(array($source),$language,$block_type);$GLOBALS['nc6_translation_write']=false;$rows=$wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE BINARY original=%s",$source),ARRAY_A);}
        if(!$rows&&!$apply){$rows=array(array('id'=>null,'translated'=>null,'block_type'=>$block_type));}
        foreach($rows as $row){
            if($row['translated']===$translation&&(int)$row['block_type']===$block_type){continue;}
            $report['writes'][]=array('review_id'=>$entry['id'],'source'=>$source,'old_translation'=>$row['translated'],'new_translation'=>$translation,'table'=>$table,'type'=>$block_type?'block':'regular','action'=>$row['translated']===null||$row['translated']===''?'translate':'update-reviewed-existing','row_id'=>$row['id']);
            if($apply){$GLOBALS['nc6_translation_write']=true;$query->update_strings(array(array('id'=>$row['id'],'original'=>$source,'translated'=>$translation,'status'=>2,'block_type'=>$block_type)),$language,array('id','original','translated','status','block_type'));$GLOBALS['nc6_translation_write']=false;}
        }
    }
    if(isset($gettext_sources[$source])){
        $rows=$wpdb->get_results($wpdb->prepare("SELECT * FROM $gettext_table WHERE BINARY original=%s AND domain='nextcore-theme'",$source),ARRAY_A);
        $new_gettext = empty($rows);
        if(!$rows&&$apply){$GLOBALS['nc6_translation_write']=true;$gettext->insert_gettext_strings(array(array('original'=>$source,'translated'=>$translation,'domain'=>'nextcore-theme','context'=>'','original_plural'=>'','plural_form'=>0)),$language);$GLOBALS['nc6_translation_write']=false;$rows=$wpdb->get_results($wpdb->prepare("SELECT * FROM $gettext_table WHERE BINARY original=%s AND domain='nextcore-theme'",$source),ARRAY_A);}
        if(!$rows&&!$apply){$rows=array(array('id'=>null,'translated'=>null));}
        foreach($rows as $row){
            if(!empty($row['translated'])&&$row['translated']!==$source&&$row['translated']!==$translation&&!in_array($row['translated'],$known,true)){$report['conflicts'][]=array('source'=>$source,'old'=>$row['translated'],'proposed'=>$translation,'table'=>$gettext_table);continue;}
            $report['writes'][]=array('review_id'=>$entry['id'],'source'=>$source,'old_translation'=>$new_gettext?null:$row['translated'],'new_translation'=>$translation,'table'=>$gettext_table,'type'=>'gettext nextcore-theme','action'=>$new_gettext?'create':'apply-reviewed','row_id'=>$row['id']);
            if($apply){$GLOBALS['nc6_translation_write']=true;$gettext->update_gettext_strings(array(array('id'=>$row['id'],'original'=>$source,'translated'=>$translation,'status'=>2)),$language,array('id','original','translated','status'));$GLOBALS['nc6_translation_write']=false;}
        }
    }
}
$report['after_counts']=$counts();
while(ob_get_level()){ob_end_clean();}
file_put_contents(__DIR__.'/translation-'.($apply?'apply-'.gmdate('Ymd-His'):'dry-run').'.json',wp_json_encode($report,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
echo wp_json_encode(array('mode'=>$report['mode'],'writes'=>count($report['writes']),'conflicts'=>$report['conflicts']),JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
