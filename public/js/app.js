/*
class Detective
{
    constructor(url) {
        this.url = url;
        this.poll();
    }

    async poll() {
        let response = await fetch(this.url);
        let data = await response.json();

        if (data.isReady) {
            window.location.reload();
        } else {
            window.setTimeout(() => this.poll(), 2000);
        }
    }
}
*/