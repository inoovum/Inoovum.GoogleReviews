<?php
namespace Inoovum\GoogleReviews\Fusion;

use Neos\Flow\Annotations as Flow;
use Neos\Fusion\FusionObjects\AbstractFusionObject;

class ReviewFusion extends AbstractFusionObject {
    /**
     * @Flow\Inject
     * @var \Inoovum\GoogleReviews\Domain\Service\ReviewService
     */
    protected $reviewService;
    /**
     * @return array
     */
    public function evaluate() {
        $placeID = $this->fusionValue('placeID');
        $reviews = $this->reviewService->showReviews($placeID);
        return $reviews;
    }
}