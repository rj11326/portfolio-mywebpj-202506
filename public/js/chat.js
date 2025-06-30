/******/ (() => { // webpackBootstrap
/*!******************************!*\
  !*** ./resources/js/chat.js ***!
  \******************************/
function _createForOfIteratorHelper(r, e) { var t = "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (!t) { if (Array.isArray(r) || (t = _unsupportedIterableToArray(r)) || e && r && "number" == typeof r.length) { t && (r = t); var _n = 0, F = function F() {}; return { s: F, n: function n() { return _n >= r.length ? { done: !0 } : { done: !1, value: r[_n++] }; }, e: function e(r) { throw r; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var o, a = !0, u = !1; return { s: function s() { t = t.call(r); }, n: function n() { var r = t.next(); return a = r.done, r; }, e: function e(r) { u = !0, o = r; }, f: function f() { try { a || null == t["return"] || t["return"](); } finally { if (u) throw o; } } }; }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
window.messageComponent = function (initialThreadId, initialCompanyName) {
  var mySenderType = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 0;
  var apiBase = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : '/messages/';
  return {
    messages: [],
    threadId: initialThreadId,
    selectedThreadId: initialThreadId,
    companyName: initialCompanyName || '',
    body: '',
    filesList: [],
    mySenderType: mySenderType,
    init: function init() {
      if (this.threadId) this.fetchMessages();
    },
    switchThread: function switchThread(newId, newCompanyName) {
      this.threadId = newId;
      this.selectedThreadId = newId;
      this.companyName = newCompanyName;
      this.messages = [];
      if (this.threadId) this.fetchMessages();
    },
    fetchMessages: function fetchMessages() {
      var _this = this;
      if (!this.threadId) return;
      fetch("".concat(apiBase).concat(this.threadId)).then(function (res) {
        return res.json();
      }).then(function (data) {
        _this.messages = data.messages;
        _this.$nextTick(function () {
          var list = document.getElementById('messages-list');
          if (list) list.scrollTop = list.scrollHeight;
        });
      });
    },
    sendMessage: function sendMessage() {
      var _this2 = this;
      if (!this.threadId || !this.body) return;
      var formData = new FormData();
      formData.append('body', this.body);
      var _iterator = _createForOfIteratorHelper(this.filesList),
        _step;
      try {
        for (_iterator.s(); !(_step = _iterator.n()).done;) {
          var file = _step.value;
          formData.append('files[]', file);
        }
      } catch (err) {
        _iterator.e(err);
      } finally {
        _iterator.f();
      }
      formData.append('_token', document.querySelector('meta[name=csrf-token]').content);
      fetch("".concat(apiBase).concat(this.threadId), {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
          'Accept': 'application/json'
        },
        body: formData
      }).then(function (res) {
        return res.json();
      }).then(function (data) {
        _this2.messages = data.messages;
        _this2.body = '';
        _this2.filesList = [];
        _this2.$refs.fileInput.value = null;
        _this2.$nextTick(function () {
          var list = document.getElementById('messages-list');
          if (list) list.scrollTop = list.scrollHeight;
        });
      });
    }
  };
};
/******/ })()
;