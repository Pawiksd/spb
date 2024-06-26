<?php

namespace App\Jobs;

use App\Models\Artist;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Nesk\Puphpeteer\Puppeteer;
use Nesk\Rialto\Data\JsFunction;

class FetchArtistContactInfoFromWebsite implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $artist;

    public function __construct(Artist $artist)
    {
        $this->artist = $artist;
    }

    public function handle()
    {
        $puppeteer = new Puppeteer;
        $browser = $puppeteer->launch();
        $page = $browser->newPage();
        $page->goto('https://open.spotify.com/artist/' . $this->artist->spotify_id);

        $aboutSection = $page->evaluate(JsFunction::createWithBody("
            const aboutSection = Array.from(document.querySelectorAll('h2')).find(element => element.textContent.includes('About'));
            if (aboutSection) {
                const aboutButton = aboutSection.nextElementSibling.querySelector('button');
                if (aboutButton) {
                    aboutButton.click();
                    setTimeout(() => {
                        const aboutModal = document.querySelector('div.ReactModalPortal');
                        const email = aboutModal.querySelector('a[href^=\"mailto:\"]')?.textContent || null;
                        const instagram = aboutModal.querySelector('a[href*=\"instagram.com\"]')?.href || null;
                        const facebook = aboutModal.querySelector('a[href*=\"facebook.com\"]')?.href || null;
                        const website = aboutModal.querySelector('a[href^=\"http\"]')?.href || null;
                        const youtube = aboutModal.querySelector('a[href*=\"youtube.com\"]')?.href || null;

                        return { email, instagram, facebook, website, youtube };
                    }, 2000); // Dajemy trochę czasu na załadowanie się popupu
                }
            }
            return {};
        "));

        $this->artist->update([
            'email' => $aboutSection['email'] ?? $this->artist->email,
            'instagram' => $aboutSection['instagram'] ?? $this->artist->instagram,
            'facebook' => $aboutSection['facebook'] ?? $this->artist->facebook,
            'website' => $aboutSection['website'] ?? $this->artist->website,
            'youtube' => $aboutSection['youtube'] ?? $this->artist->youtube,
        ]);

        $browser->close();
    }
}
