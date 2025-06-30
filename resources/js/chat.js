window.messageComponent = function(initialThreadId, initialCompanyName, mySenderType = 0, apiBase = '/messages/') {
    return {
        messages: [],
        threadId: initialThreadId,
        selectedThreadId: initialThreadId,
        companyName: initialCompanyName || '',
        body: '',
        filesList: [],
        mySenderType: mySenderType,
        init() {
            if (this.threadId) this.fetchMessages();
        },
        switchThread(newId, newCompanyName) {
            this.threadId = newId;
            this.selectedThreadId = newId;
            this.companyName = newCompanyName;
            this.messages = [];
            if (this.threadId) this.fetchMessages();
        },
        fetchMessages() {
            if (!this.threadId) return;
            fetch(`${apiBase}${this.threadId}`)
                .then(res => res.json())
                .then(data => {
                    this.messages = data.messages;
                    this.$nextTick(() => {
                        let list = document.getElementById('messages-list');
                        if (list) list.scrollTop = list.scrollHeight;
                    });
                });
        },
        sendMessage() {
            if (!this.threadId || !this.body) return;

            let formData = new FormData();
            formData.append('body', this.body);
            for (let file of this.filesList) {
                formData.append('files[]', file);
            }
            formData.append('_token', document.querySelector('meta[name=csrf-token]').content);

            fetch(`${apiBase}${this.threadId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                this.messages = data.messages;
                this.body = '';
                this.filesList = [];
                this.$refs.fileInput.value = null;
                this.$nextTick(() => {
                    let list = document.getElementById('messages-list');
                    if (list) list.scrollTop = list.scrollHeight;
                });
            });
        }
    }
}
