/******/ (() => { // webpackBootstrap
/*!*****************************************!*\
  !*** ./resources/js/user-jobs-index.js ***!
  \*****************************************/
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _createForOfIteratorHelper(r, e) { var t = "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (!t) { if (Array.isArray(r) || (t = _unsupportedIterableToArray(r)) || e && r && "number" == typeof r.length) { t && (r = t); var _n = 0, F = function F() {}; return { s: F, n: function n() { return _n >= r.length ? { done: !0 } : { done: !1, value: r[_n++] }; }, e: function e(r) { throw r; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var o, a = !0, u = !1; return { s: function s() { t = t.call(r); }, n: function n() { var r = t.next(); return a = r.done, r; }, e: function e(r) { u = !0, o = r; }, f: function f() { try { a || null == t["return"] || t["return"](); } finally { if (u) throw o; } } }; }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
function ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }
function _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { _defineProperty(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }
function _defineProperty(e, r, t) { return (r = _toPropertyKey(r)) in e ? Object.defineProperty(e, r, { value: t, enumerable: !0, configurable: !0, writable: !0 }) : e[r] = t, e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
function jobFilter() {
  return {
    // --- フィルター開閉 ---
    filterOpen: false,
    // --- 検索条件 ---
    keyword: '',
    tags: [],
    activeTags: [],
    employmentTypes: [],
    salary: 300,
    selectedJobCategoryIds: [],
    selectedJobCategoryNames: [],
    selectedLocationIds: [],
    selectedLocationNames: [],
    jobCategories: [],
    locationAreas: [],
    // --- 結果リスト ---
    jobs: [],
    // --- ページネーション ---
    currentPage: 1,
    lastPage: 1,
    // --- モーダルフラグ ---
    openJobCategoryModal: false,
    openLocationModal: false,
    // --- 並び替え ---
    sort: 'date',
    // --- 求人保存機能 ---
    savedJobIds: [],
    // --- 初期化 ---
    init: function init() {
      var _this = this;
      var params = new URLSearchParams(window.location.search);
      this.keyword = params.get('q') || '';
      this.salary = params.get('salary') ? Number(params.get('salary')) : 300;
      if (params.get('job_categories')) {
        this.selectedJobCategoryIds = params.get('job_categories').split(',').filter(function (x) {
          return x;
        });
      }
      if (params.get('locations')) {
        this.selectedLocationIds = params.get('locations').split(',').filter(function (x) {
          return x;
        });
      }
      this.filterOpen = window.innerWidth >= 768;
      // マスタ取得
      fetch('/api/tags').then(function (res) {
        return res.json();
      }).then(function (data) {
        return _this.tags = data.tags;
      });
      this.fetchSavedJobIds();
      this.fetchCategories();
      this.fetchLocationAreas();
      this.fetchJobs();
    },
    // --- 職種マスタ取得 ---
    fetchCategories: function fetchCategories() {
      var _this2 = this;
      fetch('/api/job-categories').then(function (res) {
        return res.json();
      }).then(function (data) {
        _this2.jobCategories = data.categories.map(function (parent) {
          return _objectSpread(_objectSpread({}, parent), {}, {
            open: false
          });
        });
        _this2.confirmJobCategories(false);
      });
    },
    // --- 勤務地マスタ取得 ---
    fetchLocationAreas: function fetchLocationAreas() {
      var _this3 = this;
      fetch('/api/location-areas').then(function (res) {
        return res.json();
      }).then(function (data) {
        _this3.locationAreas = data.areas.map(function (area) {
          return _objectSpread(_objectSpread({}, area), {}, {
            open: false
          });
        });
        _this3.confirmLocations(false);
      });
    },
    confirmJobCategories: function confirmJobCategories() {
      var fetch = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : true;
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
      if (fetch) this.fetchJobs();
    },
    confirmLocations: function confirmLocations() {
      var fetch = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : true;
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
      if (fetch) this.fetchJobs();
    },
    toggleTag: function toggleTag(tagId) {
      var idx = this.activeTags.indexOf(tagId);
      if (idx !== -1) {
        this.activeTags.splice(idx, 1);
      } else {
        this.activeTags.push(tagId);
      }
      this.fetchJobs();
    },
    fetchSavedJobIds: function fetchSavedJobIds() {
      var _this4 = this;
      fetch('/api/saved-jobs', {
        credentials: 'include'
      }).then(function (res) {
        if (res.status === 401) {
          _this4.savedJobIds = [];
          return;
        }
        return res.json();
      }).then(function (data) {
        if (data) _this4.savedJobIds = data.saved_job_ids || [];
      })["catch"](function (err) {
        _this4.savedJobIds = [];
      });
    },
    toggleSave: function toggleSave(jobId) {
      var _this5 = this;
      fetch("/api/jobs/".concat(jobId, "/save"), {
        method: 'POST',
        credentials: 'include',
        headers: {
          'Accept': 'application/json',
          'X-XSRF-TOKEN': getCookie('XSRF-TOKEN')
        }
      }).then(function (res) {
        console.log(res);
        if (!res.ok) throw new Error('通信エラー');
        return res.json();
      }).then(function (data) {
        if (data.result === 'saved') {
          _this5.savedJobIds.push(jobId);
        } else if (data.result === 'removed') {
          _this5.savedJobIds = _this5.savedJobIds.filter(function (id) {
            return id !== jobId;
          });
        }
      })["catch"](function (err) {
        console.log(err);
      });
    },
    isSaved: function isSaved(jobId) {
      return this.savedJobIds.includes(jobId);
    },
    fetchJobs: function fetchJobs() {
      var _this6 = this;
      var page = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 1;
      var params = new URLSearchParams();
      if (this.keyword) params.append('q', this.keyword);
      if (this.salary) params.append('salary', this.salary);

      // タグ
      if (this.activeTags && this.activeTags.length > 0) {
        params.append('tags', this.activeTags.join(','));
      }
      // 雇用形態
      if (this.employmentTypes && this.employmentTypes.length > 0) {
        params.append('employment_types', this.employmentTypes.join(','));
      }
      // 職種
      if (this.selectedJobCategoryIds && this.selectedJobCategoryIds.length > 0) {
        params.append('job_categories', this.selectedJobCategoryIds.join(','));
      }
      // 勤務地
      if (this.selectedLocationIds && this.selectedLocationIds.length > 0) {
        params.append('locations', this.selectedLocationIds.join(','));
      }
      // 並び替え
      params.append('sort', this.sort);

      // ページ番号
      params.append('page', page);
      fetch("/api/jobs?page=".concat(page, "&") + params.toString()).then(function (res) {
        return res.json();
      }).then(function (data) {
        _this6.jobs = data.jobs;
        _this6.currentPage = data.current_page;
        _this6.lastPage = data.last_page;
      });
    }
  };
}
function getCookie(name) {
  var value = "; ".concat(document.cookie);
  var parts = value.split("; ".concat(name, "="));
  if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift());
}
window.getCookie = getCookie;
window.jobFilter = jobFilter;
/******/ })()
;