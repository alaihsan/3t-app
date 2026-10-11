document.addEventListener('alpine:init', () => {
    Alpine.data('studentSearch', (options = {}) => ({
        searchQuery: '',
        activeClass: 'all',
        activeCategory: 'all',
        loading: false,
        showResults: false,
        results: [],
        init() {
            if (options.autoSearchOnInit ?? true) {
                this.performSearch();
            }
        },
        setClassFilter(cls) {
            this.activeClass = this.activeClass === cls ? 'all' : cls;
            this.performSearch();
        },
        setCategoryFilter(cat) {
            this.activeCategory = this.activeCategory === cat ? 'all' : cat;
            this.performSearch();
        },
        performSearch() {
            this.loading = true;
            const params = new URLSearchParams();
            if (this.searchQuery) params.append('q', this.searchQuery);
            if (this.activeClass !== 'all') params.append('class', this.activeClass);
            if (this.activeCategory !== 'all') params.append('category', this.activeCategory);

            fetch(`/api/search?${params.toString()}`)
                .then((res) => res.json())
                .then((data) => {
                    this.results = data.data || [];
                    this.loading = false;
                    if (this.searchQuery || this.activeClass !== 'all' || this.activeCategory !== 'all') {
                        this.showResults = true;
                    }
                })
                .catch((err) => {
                    console.error(err);
                    this.loading = false;
                });
        },
    }));
});
