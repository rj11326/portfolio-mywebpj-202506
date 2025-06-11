/******/ (() => { // webpackBootstrap
/*!**************************************************!*\
  !*** ./resources/js/user-applications-create.js ***!
  \**************************************************/
function _createForOfIteratorHelper(r, e) { var t = "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (!t) { if (Array.isArray(r) || (t = _unsupportedIterableToArray(r)) || e && r && "number" == typeof r.length) { t && (r = t); var _n = 0, F = function F() {}; return { s: F, n: function n() { return _n >= r.length ? { done: !0 } : { done: !1, value: r[_n++] }; }, e: function e(r) { throw r; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var o, a = !0, u = !1; return { s: function s() { t = t.call(r); }, n: function n() { var r = t.next(); return a = r.done, r; }, e: function e(r) { u = !0, o = r; }, f: function f() { try { a || null == t["return"] || t["return"](); } finally { if (u) throw o; } } }; }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
document.addEventListener('DOMContentLoaded', function () {
  var input = document.getElementById('resume');
  var btn = document.getElementById('resume-btn');
  var list = document.getElementById('resume-list');
  var files = [];
  btn.addEventListener('click', function () {
    return input.click();
  });
  input.addEventListener('change', function (e) {
    var _iterator = _createForOfIteratorHelper(e.target.files),
      _step;
    try {
      for (_iterator.s(); !(_step = _iterator.n()).done;) {
        var file = _step.value;
        files.push(file);
      }
    } catch (err) {
      _iterator.e(err);
    } finally {
      _iterator.f();
    }
    renderList();
    input.value = '';
  });
  function renderList() {
    list.innerHTML = '';
    files.forEach(function (file, idx) {
      var li = document.createElement('li');
      li.className = "flex items-center gap-2 mb-1";
      li.innerHTML = "\n                <span>".concat(file.name, "</span>\n                <button type=\"button\" class=\"text-red-500 text-lg\" onclick=\"removeFile(").concat(idx, ")\">&times;</button>\n            ");
      list.appendChild(li);
    });
  }
  window.removeFile = function (idx) {
    files.splice(idx, 1);
    renderList();
  };
  document.querySelector('form').addEventListener('submit', function (e) {
    if (files.length) {
      e.preventDefault();
      var formData = new FormData(this);
      formData["delete"]('resume[]');
      files.forEach(function (file) {
        return formData.append('resume[]', file);
      });
      fetch(this.action, {
        method: this.method,
        body: formData,
        headers: {
          'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
        }
      }).then(function (response) {
        if (response.redirected) {
          window.location.href = response.url;
        } else {
          window.location.reload();
        }
      });
    }
  });
});
/******/ })()
;