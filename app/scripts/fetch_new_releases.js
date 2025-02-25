const puppeteer = require('puppeteer');

(async () => {
    try {
        const browser = await puppeteer.launch({
            headless: 'new',
            args: [
                '--no-sandbox',
                '--disable-setuid-sandbox',
                '--disable-dev-shm-usage',
                '--disable-gpu',
                '--no-zygote',
                '--disable-software-rasterizer',
                '--disable-features=VizDisplayCompositor',
                '--disable-features=NetworkService'
            ],
            ignoreHTTPSErrors: true
        });

        const page = await browser.newPage();
        await page.goto('https://open.spotify.com/search', { waitUntil: 'networkidle2', timeout: 60000 });

        // Find the "New Releases" link and navigate to it
        const newReleasesLink = await page.evaluate(() => {
            const link = Array.from(document.querySelectorAll('a')).find(a => a.textContent.includes('New Releases'));
            return link ? link.href : null;
        });

        if (!newReleasesLink) {
            console.log('New Releases link not found');
            await browser.close();
            return;
        }

        await page.goto(newReleasesLink, { waitUntil: 'networkidle2', timeout: 60000 });

        // Click the "Show all" button
        const showAllLink = await page.evaluate(() => {
            const link = Array.from(document.querySelectorAll('a')).find(a => a.textContent.includes('Show all'));
            return link ? link.href : null;
        });

        if (!showAllLink) {
            console.log('Show all link not found');
            await browser.close();
            return;
        }

        await page.goto(showAllLink, { waitUntil: 'networkidle2', timeout: 60000 });

        // Extract album and artist details
        const albums = await page.evaluate(() => {
            const albumCards = document.querySelectorAll('div[data-encore-id="card"]');
            return Array.from(albumCards).map(card => {
                const albumLink = card.querySelector('a[href^="/album/"]');
                const artistLink = card.querySelector('a[href^="/artist/"]');
                return {
                    album_id: albumLink ? albumLink.getAttribute('href').split('/').pop() : null,
                    album_title: albumLink ? albumLink.textContent.trim() : null,
                    artist_id: artistLink ? artistLink.getAttribute('href').split('/').pop() : null,
                    artist_name: artistLink ? artistLink.textContent.trim() : null,
                    release_date: new Date().toISOString().split('T')[0]
                };
            });
        });

        console.log(JSON.stringify(albums, null, 2));
        await browser.close();
    } catch (error) {
        console.error('Error:', error);
    }
})();
