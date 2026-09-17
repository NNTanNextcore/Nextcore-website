const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const vm = require('node:vm');

const source = fs.readFileSync(path.join(__dirname, '../assets/js/home-section-snap.js'), 'utf8');
let y = 0;
let wheel;
let nextFrame;
let now = 0;
let modalDisplay = 'none';

class Element {
    constructor(top = 0, height = 0, id = '') {
        this.top = top;
        this.height = height;
        this.id = id;
        this.parentElement = null;
        this.scrollHeight = height;
        this.clientHeight = height;
        this.scrollTop = 0;
        this.classList = {contains: () => false, add: () => {}};
    }
    matches(selector) { return selector === 'section'; }
    closest() { return null; }
    getAttribute() { return null; }
    getBoundingClientRect() { return {top: this.top - y, bottom: this.top + this.height - y, height: this.height}; }
    getClientRects() { return modalDisplay === 'none' ? [] : [{}]; }
    setAttribute() {}
    removeAttribute() {}
}

const body = new Element();
const home = new Element();
home.parentElement = body;
const hero = new Element(0, 1100, 'home');
const about = new Element(1100, 900, 'about');
const stats = new Element(2000, 400, 'stats');
home.children = [hero, about, stats];
home.children.forEach(node => { node.parentElement = home; });
const header = new Element(0, 88);
const modal = new Element();
modal.tagName = 'DIV';
modal.parentElement = body;
const root = new Element();
root.scrollHeight = 3000;
root.style = {setProperty() {}};
root.hasAttribute = () => false;
const document = {
    body,
    documentElement: root,
    readyState: 'loading',
    querySelector(selector) {
        if (selector === 'body.nextcore-homepage .nextcore-home') return home;
        if (selector === '.nextcore-site-header') return header;
        return null;
    },
    querySelectorAll() { return [modal]; },
    getElementById() { return null; },
    addEventListener() {}
};
const window = {
    addEventListener(name, handler) { if (name === 'wheel') wheel = handler; },
    scrollTo({top}) { y = top; }
};
const context = {
    document, window, Element, innerHeight: 900,
    matchMedia: () => ({matches: false, addEventListener() {}}),
    getComputedStyle: node => ({position: node === header ? 'fixed' : 'static', overflowY: 'visible',
        display: node === modal ? modalDisplay : 'block', visibility: 'visible', opacity: '1'}),
    requestAnimationFrame(callback) { nextFrame = callback; return 1; },
    cancelAnimationFrame() { nextFrame = null; },
    performance: {now: () => now},
    setTimeout() { return 1; }, clearTimeout() {},
    MutationObserver: class { observe() {} },
    console
};
Object.defineProperty(context, 'scrollY', {get: () => y});
vm.runInNewContext(source, context);
assert.equal(window.nextcoreHomeSnapStatus().blocked, false, 'hidden Elementor modal must not block snapping');

function scrollOnce(deltaY) {
    let prevented = false;
    wheel({deltaY, deltaX: 0, deltaMode: 0, clientX: 10, clientY: 200,
        cancelable: true, target: hero, preventDefault() { prevented = true; }});
    assert.equal(prevented, true, 'one wheel event should be captured');
    assert.equal(typeof nextFrame, 'function', 'one wheel event should start an animation');
    now += 1000;
    nextFrame(now);
}

scrollOnce(1);
assert.equal(y, 1012, 'downward wheel should land at About below the 88px header');
scrollOnce(-1);
assert.equal(y, 0, 'upward wheel should return to Home');
modalDisplay = 'block';
assert.equal(window.nextcoreHomeSnapStatus().blockingReason, 'div', 'visible modal should block snapping');
console.log('Single wheel moves Home -> About -> Home');
