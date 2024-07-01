const puppeteer = require('puppeteer');

(async () => {
    try {
        const artistId = process.argv[2];
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
        await page.setViewport({ width: 1920, height: 1080 });
        await page.goto(`https://open.spotify.com/artist/${artistId}`, { waitUntil: 'networkidle2', timeout: 60000 });

        await page.content();

        const artistName = await page.evaluate(() => {
            const metaTag = document.querySelector('meta[property="og:title"]');
            return metaTag ? metaTag.getAttribute('content') : null;
        });

        if (!artistName) {
            await browser.close();
            return;
        }

        const buttonClicked = await page.evaluate((artistName) => {
            const button = document.querySelector(`button[aria-label="${artistName}"]`);
            if (button) {
                button.click();
                return true;
            }
            return false;
        }, artistName);

        let aboutSection = {};
        if (buttonClicked) {
            aboutSection = await page.evaluate(() => {
                return new Promise((resolve) => {
                    setTimeout(() => {
                        const aboutModals = document.querySelectorAll('div.ReactModalPortal');
                        const aboutModal = aboutModals[aboutModals.length - 1];
                        const email = aboutModal?.querySelector('a[href^="mailto:"]')?.textContent || null;

                        const links = new Set();
                        const instagram = aboutModal?.querySelector('a[href*="instagram.com"]')?.href || null;
                        if (instagram) links.add(instagram);

                        const facebook = aboutModal?.querySelector('a[href*="facebook.com"]')?.href || null;
                        if (facebook) links.add(facebook);

                        const twitter = aboutModal?.querySelector('a[href*="twitter.com"]')?.href || null;
                        if (twitter) links.add(twitter);

                        const website = Array.from(aboutModal?.querySelectorAll('a[href^="http"]') || [])
                            .map(link => link.href)
                            .find(href => !links.has(href)) || null;
                        if (website) links.add(website);

                        const youtube = aboutModal?.querySelector('a[href*="youtube.com"]')?.href || null;
                        if (youtube) links.add(youtube);

                        resolve({ email, instagram, facebook, twitter, website, youtube });
                    }, 6000);
                });
            });
        }

        console.log(JSON.stringify(aboutSection));
        await browser.close();
    } catch (error) {
        console.error('Error:', error);
    }
})();
