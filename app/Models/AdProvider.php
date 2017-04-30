<?php

namespace App\Models;

class AdProvider
{
    private $adSet;

    public function __construct(AdSet $adSet) {
        $this->adSet = $adSet->getAll();
    }

    public function leaderboard() {
        return $this->adSet['leaderboard'];
    }

    public function bannerRightRail() {
        return $this->adSet['banner-right-rail'];
    }

    public function bannerBeforeContent() {
        return $this->adSet['banner-before-content'];
    }

    public function bannerWithinContent() {
        return $this->adSet['banner-within-content'];
    }

    public function bannerAfterContent() {
        return $this->adSet['banner-after-content'];
    }

    public function contentAfterContent() {
        return $this->adSet['content-after-content'];
    }

    public function contentRightRail() {
        return $this->adSet['content-right-rail'];
    }

    public function insideContent($section) {
        return $this->adSet['inside-content-' . $section];
    }
}