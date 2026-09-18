(function (blocks, element, components, blockEditor, serverSideRender, i18n) {
    'use strict';
    var el = element.createElement;
    var __ = i18n.__;
    var InspectorControls = blockEditor.InspectorControls;
    var PanelBody = components.PanelBody;
    var RangeControl = components.RangeControl;
    var SelectControl = components.SelectControl;
    var ToggleControl = components.ToggleControl;
    var TextControl = components.TextControl;
    var ServerSideRender = serverSideRender;

    blocks.registerBlockType('nextcore/team-grid', {
        title: __('Danh sách đội ngũ', 'nextcore-team'),
        category: 'nextcore-team',
        icon: 'groups',
        edit: function (props) {
            var a = props.attributes;
            function set(key, value) { var o = {}; o[key] = value; props.setAttributes(o); }
            return [
                el(InspectorControls, { key: 'controls' },
                    el(PanelBody, { title: __('Hiển thị', 'nextcore-team'), initialOpen: true },
                        el(RangeControl, { label: __('Cột desktop', 'nextcore-team'), min: 1, max: 6, value: a.columns, onChange: function(v){set('columns', v);} }),
                        el(RangeControl, { label: __('Cột tablet', 'nextcore-team'), min: 1, max: 4, value: a.tablet_columns, onChange: function(v){set('tablet_columns', v);} }),
                        el(RangeControl, { label: __('Cột mobile', 'nextcore-team'), min: 1, max: 2, value: a.mobile_columns, onChange: function(v){set('mobile_columns', v);} }),
                        el(RangeControl, { label: __('Khoảng cách', 'nextcore-team'), min: 0, max: 100, value: a.gap, onChange: function(v){set('gap', v);} }),
                        el(RangeControl, { label: __('Bo góc', 'nextcore-team'), min: 0, max: 100, value: a.radius, onChange: function(v){set('radius', v);} }),
                        el(SelectControl, { label: __('Tỷ lệ ảnh', 'nextcore-team'), value: a.image_ratio, options: [
                            {label:'1:1', value:'1-1'}, {label:'4:5', value:'4-5'}, {label:'3:4', value:'3-4'}, {label:'16:9', value:'16-9'}
                        ], onChange: function(v){set('image_ratio', v);} }),
                        el(ToggleControl, { label: __('Hiện tiêu đề section', 'nextcore-team'), checked: a.show_section_heading, onChange: function(v){set('show_section_heading', v);} }),
                        el(ToggleControl, { label: __('Hiện chức danh', 'nextcore-team'), checked: a.show_role, onChange: function(v){set('show_role', v);} }),
                        el(ToggleControl, { label: __('Hiện icon liên hệ', 'nextcore-team'), checked: a.show_contact, onChange: function(v){set('show_contact', v);} }),
                        el(TextControl, { label: __('Slug phần (tùy chọn)', 'nextcore-team'), value: a.section, onChange: function(v){set('section', v);} }),
                        el(RangeControl, { label: __('Giới hạn số thành viên mỗi phần', 'nextcore-team'), min: -1, max: 50, value: a.limit, onChange: function(v){set('limit', v);} })
                    )
                ),
                el('div', { key: 'preview' }, el(ServerSideRender, { block: 'nextcore/team-grid', attributes: a }))
            ];
        },
        save: function () { return null; }
    });
})(window.wp.blocks, window.wp.element, window.wp.components, window.wp.blockEditor, window.wp.serverSideRender, window.wp.i18n);
