/******/ (() => { // webpackBootstrap
/*!***********************************!*\
  !*** ./resources/js/user-home.js ***!
  \***********************************/
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _createForOfIteratorHelper(r, e) { var t = "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (!t) { if (Array.isArray(r) || (t = _unsupportedIterableToArray(r)) || e && r && "number" == typeof r.length) { t && (r = t); var _n = 0, F = function F() {}; return { s: F, n: function n() { return _n >= r.length ? { done: !0 } : { done: !1, value: r[_n++] }; }, e: function e(r) { throw r; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var o, a = !0, u = !1; return { s: function s() { t = t.call(r); }, n: function n() { var r = t.next(); return a = r.done, r; }, e: function e(r) { u = !0, o = r; }, f: function f() { try { a || null == t["return"] || t["return"](); } finally { if (u) throw o; } } }; }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
function ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }
function _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { _defineProperty(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }
function _defineProperty(e, r, t) { return (r = _toPropertyKey(r)) in e ? Object.defineProperty(e, r, { value: t, enumerable: !0, configurable: !0, writable: !0 }) : e[r] = t, e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
function jobSearchBar() {
  return {
    // --- キーワード ---
    keyword: '',
    // --- 職種選択モーダル ---
    openJobCategoryModal: false,
    jobCategories: [],
    selectedJobCategoryIds: [],
    selectedJobCategoryNames: [],
    // --- 勤務地選択モーダル ---
    openLocationModal: false,
    locationAreas: [],
    selectedLocationIds: [],
    selectedLocationNames: [],
    // --- 年収選択 ---
    selectedSalary: null,
    salaryOptions: Array.from({
      length: 8
    }, function (_, i) {
      return 300 + i * 100;
    }),
    // --- 職種API ---
    fetchCategories: function fetchCategories() {
      var _this = this;
      fetch('/api/job-categories').then(function (res) {
        return res.json();
      }).then(function (data) {
        _this.jobCategories = data.categories.map(function (parent) {
          return _objectSpread(_objectSpread({}, parent), {}, {
            open: false
          });
        });
      });
    },
    // --- 勤務地API ---
    fetchLocationAreas: function fetchLocationAreas() {
      var _this2 = this;
      fetch('/api/location-areas').then(function (res) {
        return res.json();
      }).then(function (data) {
        _this2.locationAreas = data.areas.map(function (area) {
          return _objectSpread(_objectSpread({}, area), {}, {
            open: false
          });
        });
      });
    },
    confirmJobCategories: function confirmJobCategories() {
      this.selectedJobCategoryNames = [];
      var _iterator = _createForOfIteratorHelper(this.jobCategories),
        _step;
      try {
        for (_iterator.s(); !(_step = _iterator.n()).done;) {
          var parent = _step.value;
          var _iterator2 = _createForOfIteratorHelper(parent.children),
            _step2;
          try {
            for (_iterator2.s(); !(_step2 = _iterator2.n()).done;) {
              var child = _step2.value;
              if (this.selectedJobCategoryIds.includes(child.id.toString())) {
                this.selectedJobCategoryNames.push(child.name);
              }
            }
          } catch (err) {
            _iterator2.e(err);
          } finally {
            _iterator2.f();
          }
        }
      } catch (err) {
        _iterator.e(err);
      } finally {
        _iterator.f();
      }
      this.openJobCategoryModal = false;
    },
    confirmLocations: function confirmLocations() {
      this.selectedLocationNames = [];
      var _iterator3 = _createForOfIteratorHelper(this.locationAreas),
        _step3;
      try {
        for (_iterator3.s(); !(_step3 = _iterator3.n()).done;) {
          var area = _step3.value;
          var _iterator4 = _createForOfIteratorHelper(area.children),
            _step4;
          try {
            for (_iterator4.s(); !(_step4 = _iterator4.n()).done;) {
              var pref = _step4.value;
              if (this.selectedLocationIds.includes(pref.id.toString())) {
                this.selectedLocationNames.push(pref.name);
              }
            }
          } catch (err) {
            _iterator4.e(err);
          } finally {
            _iterator4.f();
          }
        }
      } catch (err) {
        _iterator3.e(err);
      } finally {
        _iterator3.f();
      }
      this.openLocationModal = false;
    }
  };
}
function salaryDropdown() {
  return {
    open: false,
    selectedSalary: null,
    salaryOptions: Array.from({
      length: 15
    }, function (_, i) {
      return 300 + i * 50;
    }),
    get selectedSalaryLabel() {
      return this.selectedSalary ? this.selectedSalary + '万円以上' : '年収';
    },
    select: function select(val) {
      this.selectedSalary = val;
      this.open = false;
    }
  };
}
window.jobSearchBar = jobSearchBar;
window.salaryDropdown = salaryDropdown;
/******/ })()
;