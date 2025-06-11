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
        }, (_, i) => 300 + i * 100),
        // --- 職種API ---
        fetchCategories() {
            fetch('/api/job-categories')
                .then(res => res.json())
                .then(data => {
                    this.jobCategories = data.categories.map(parent => ({
                        ...parent,
                        open: false,
                    }));
                });
        },
        // --- 勤務地API ---
        fetchLocationAreas() {
            fetch('/api/location-areas')
                .then(res => res.json())
                .then(data => {
                    this.locationAreas = data.areas.map(area => ({
                        ...area,
                        open: false,
                    }));
                });
        },
        confirmJobCategories() {
            this.selectedJobCategoryNames = [];
            for (let parent of this.jobCategories) {
                for (let child of parent.children) {
                    if (this.selectedJobCategoryIds.includes(child.id.toString())) {
                        this.selectedJobCategoryNames.push(child.name);
                    }
                }
            }
            this.openJobCategoryModal = false;
        },
        confirmLocations() {
            this.selectedLocationNames = [];
            for (let area of this.locationAreas) {
                for (let pref of area.children) {
                    if (this.selectedLocationIds.includes(pref.id.toString())) {
                        this.selectedLocationNames.push(pref.name);
                    }
                }
            }
            this.openLocationModal = false;
        }
    }
}

function salaryDropdown() {
    return {
        open: false,
        selectedSalary: null,
        salaryOptions: Array.from({
            length: 15
        }, (_, i) => 300 + i * 50),
        get selectedSalaryLabel() {
            return this.selectedSalary ? this.selectedSalary + '万円以上' : '年収';
        },
        select(val) {
            this.selectedSalary = val;
            this.open = false;
        }
    }
}


window.jobSearchBar = jobSearchBar;
window.salaryDropdown = salaryDropdown;