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
        init() {
            const params = new URLSearchParams(window.location.search);
            this.keyword = params.get('q') || '';
            this.salary = params.get('salary') ? Number(params.get('salary')) : 300;
            if (params.get('job_categories')) {
                this.selectedJobCategoryIds = params.get('job_categories').split(',').filter(x => x);
            }
            if (params.get('locations')) {
                this.selectedLocationIds = params.get('locations').split(',').filter(x => x);
            }
            this.filterOpen = window.innerWidth >= 768;
            // マスタ取得
            fetch('/api/tags')
                .then(res => res.json())
                .then(data => this.tags = data.tags);
            this.fetchSavedJobIds();
            this.fetchCategories();
            this.fetchLocationAreas();
            this.fetchJobs();
        },

        // --- 職種マスタ取得 ---
        fetchCategories() {
            fetch('/api/job-categories')
                .then(res => res.json())
                .then(data => {
                    this.jobCategories = data.categories.map(parent => ({
                        ...parent,
                        open: false,
                    }));
                    this.confirmJobCategories(false);
                });
        },
        // --- 勤務地マスタ取得 ---
        fetchLocationAreas() {
            fetch('/api/location-areas')
                .then(res => res.json())
                .then(data => {
                    this.locationAreas = data.areas.map(area => ({
                        ...area,
                        open: false,
                    }));
                    this.confirmLocations(false);
                });
        },

        confirmJobCategories(fetch = true) {
            this.selectedJobCategoryNames = [];
            for (let parent of this.jobCategories) {
                for (let child of parent.children) {
                    if (this.selectedJobCategoryIds.includes(child.id.toString())) {
                        this.selectedJobCategoryNames.push(child.name);
                    }
                }
            }
            this.openJobCategoryModal = false;
            if (fetch) this.fetchJobs();
        },
        confirmLocations(fetch = true) {
            this.selectedLocationNames = [];
            for (let area of this.locationAreas) {
                for (let pref of area.children) {
                    if (this.selectedLocationIds.includes(pref.id.toString())) {
                        this.selectedLocationNames.push(pref.name);
                    }
                }
            }
            this.openLocationModal = false;
            if (fetch) this.fetchJobs();
        },

        toggleTag(tagId) {
            const idx = this.activeTags.indexOf(tagId);
            if (idx !== -1) {
                this.activeTags.splice(idx, 1);
            } else {
                this.activeTags.push(tagId);
            }
            this.fetchJobs();
        },
        fetchSavedJobIds() {
            fetch('/api/saved-jobs', {
                credentials: 'include',
            })
                .then(res => {
                    if (res.status === 401) {
                        this.savedJobIds = [];
                        return;
                    }
                    return res.json();
                })
                .then(data => {
                    if (data) this.savedJobIds = data.saved_job_ids || [];
                })
                .catch(err => {
                    this.savedJobIds = [];
                });
        },
        toggleSave(jobId) {
            fetch(`/api/jobs/${jobId}/save`, {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Accept': 'application/json',
                    'X-XSRF-TOKEN': getCookie('XSRF-TOKEN'),
                },
            })
                .then(res => {
                    console.log(res)
                    if (!res.ok) throw new Error('通信エラー');
                    return res.json();
                })
                .then(data => {
                    if (data.result === 'saved') {
                        this.savedJobIds.push(jobId);
                    } else if (data.result === 'removed') {
                        this.savedJobIds = this.savedJobIds.filter(id => id !== jobId);
                    }
                })
                .catch(err => {
                    console.log(err)
                });
        },
        isSaved(jobId) {
            return this.savedJobIds.includes(jobId);
        },
        fetchJobs(page = 1) {
            const params = new URLSearchParams();
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

            fetch(`/api/jobs?page=${page}&` + params.toString())
                .then(res => res.json())
                .then(data => {
                    this.jobs = data.jobs;
                    this.currentPage = data.current_page;
                    this.lastPage = data.last_page;
                });
        }
    }
}

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return decodeURIComponent(parts.pop().split(';').shift());
}

window.getCookie = getCookie;
window.jobFilter = jobFilter;