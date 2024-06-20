const puppeteer = require('puppeteer');
const axios = require('axios');

const API_URL = process.env.API_URL || 'http://localhost:8000/api';

(async () => {
  const browser = await puppeteer.launch();
  const page = await browser.newPage();

  // Navigate to Spotify new releases page
  await page.goto('https://spotify.com/new-releases');

  // Extract data (this is just an example; adjust selectors as needed)
  const newReleases = await page.evaluate(() => {
    return Array.from(document.querySelectorAll('.release')).map(release => ({
      artist: release.querySelector('.artist').innerText,
      title: release.querySelector('.title').innerText,
      genre: release.querySelector('.genre').innerText,
      label: release.querySelector('.label').innerText,
      email: release.querySelector('.email') ? release.querySelector('.email').innerText : null,
      instagram: release.querySelector('.instagram') ? release.querySelector('.instagram').innerText : null,
      facebook: release.querySelector('.facebook') ? release.querySelector('.facebook').innerText : null,
      website: release.querySelector('.website') ? release.querySelector('.website').innerText : null,
      youtube: release.querySelector('.youtube') ? release.querySelector('.youtube').innerText : null,
    }));
  });

  // Send data to Laravel API
  await axios.post(`${API_URL}/new-releases`, { newReleases });

  await browser.close();
})();
