<?php

namespace App\Jobs;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateSiteScore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $serverIp;

    public function __construct($serverIp)
    {
        $this->serverIp = $serverIp;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $serverUrl = 'localhost:4444';

        $desiredCapabilities = DesiredCapabilities::chrome();

        $chromeOptions = new ChromeOptions();
        $chromeOptions->addArguments(['-headless']);
        $desiredCapabilities->setCapability(ChromeOptions::CAPABILITY, $chromeOptions);

        $driver = RemoteWebDriver::create($serverUrl, $desiredCapabilities);

        try {
            $driver->get('https://analysis.codibu.com');

            $driver->findElement(WebDriverBy::xpath('/html/body/div[1]/section/div/div[2]/div/div[2]/div/form/div/input')) // find search input element
            ->sendKeys($this->serverIp) // fill the search box
            ->submit(); // submit the whole form

            sleep(2);

            $driver->manage()->window()->maximize();

            $driver->wait(500, 5*1000)->until(
                WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::id('progress-bar'))
            );
        }catch (\Exception $exception) {
            Log::critical($exception->getMessage());
        } finally {
            $driver->quit();
        }

    }
}
