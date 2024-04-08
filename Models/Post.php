<?php

namespace Models;

use Models\Interfaces\Model;
use Models\Traits\GenericModel;

class Post implements Model {
    
    use GenericModel;

    // php 8のコンストラクタのプロパティプロモーションは、インスタンス変数を自動的に設定します。
    public function __construct(
        public ?int $reply_to_id,
        public string $subject,
        public string $content,
        public ?string $file_path = null,
        public ?string $file_name = null,
        public ?string $mime_type = null,
        public ?int $size = null,
        public ?string $url = null,
        public ?DataTimeStamp $timeStamp = null
    ) {}

    public function getReply_to_id(): ?int {
        return $this->reply_to_id;
    }

    public function setReply_to_id(int $reply_to_id): void {
        $this->reply_to_id = $reply_to_id;
    }
    public function getSubject(): string {
        return $this->subject;
    }

    public function setSubject(string $subject): void {
        $this->subject = $subject;
    }
    public function getContent(): string {
        return $this->content;
    }

    public function setContent(string $content): void {
        $this->content = $content;
    }

    public function getFile_path(): string {
        return $this->file_path;
    }

    public function setFile_path(string $file_path): void {
        $this->file_path = $file_path;
    }

    public function getFile_name(): ?string {
        return $this->file_name;
    }

    public function setFile_name(string $file_name): void {
        $this->file_name = $file_name;
    }

    public function getMime_type(): ?string {
        return $this->mime_type;
    }

    public function setMime_type(string $mime_type): void {
        $this->mime_type = $mime_type;
    }

    public function getSize(): ?string {
        return $this->size;
    }

    public function setSize(string $size): void {
        $this->size = $size;
    }

    public function getUrl(): ?string {
        return $this->size; 
    }

    public function setUrl(string $size): void {
        $this->size = $size;
    }

    // public function getPerformanceScore(): ?int {
    //     return $this->performanceScore;
    // }

    // public function setPerformanceScore(int $performanceScore): void {
    //     $this->performanceScore = $performanceScore;
    // }

    // public function getMarketPrice(): ?float {
    //     return $this->marketPrice;
    // }

    // public function setMarketPrice(float $marketPrice): void {
    //     $this->marketPrice = $marketPrice;
    // }

    // public function getRsm(): ?float {
    //     return $this->rsm;
    // }

    // public function setRsm(float $rsm): void {
    //     $this->rsm = $rsm;
    // }

    // public function getPowerConsumptionW(): ?float {
    //     return $this->powerConsumptionW;
    // }

    // public function setPowerConsumptionW(float $powerConsumptionW): void {
    //     $this->powerConsumptionW = $powerConsumptionW;
    // }

    // public function getLengthM(): ?float {
    //     return $this->lengthM;
    // }

    // public function setLengthM(float $lengthM): void {
    //     $this->lengthM = $lengthM;
    // }

    // public function getWidthM(): ?float {
    //     return $this->widthM;
    // }

    // public function setWidthM(float $widthM): void {
    //     $this->widthM = $widthM;
    // }

    // public function getHeightM(): ?float {
    //     return $this->heightM;
    // }

    // public function setHeightM(float $heightM): void {
    //     $this->heightM = $heightM;
    // }

    // public function getLifespan(): ?int {
    //     return $this->lifespan;
    // }

    // public function setLifespan(int $lifespan): void {
    //     $this->lifespan = $lifespan;
    // }

    public function getTimeStamp(): ?DataTimeStamp
    {
        return $this->timeStamp;
    }

    public function setTimeStamp(DataTimeStamp $timeStamp): void
    {
        $this->timeStamp = $timeStamp;
    }
}