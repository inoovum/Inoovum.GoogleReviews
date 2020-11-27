<?php
namespace Inoovum\GoogleReviews\Domain\Service;
use Neos\Flow\Annotations as Flow;
/**
 * @Flow\Scope("singleton")
 */
class ReviewService {
    /**
     * @var array
     */
    protected $settings;
    /**
     * @param array $settings
     * @return void
     */
    public function injectSettings(array $settings) {
        $this->settings = $settings;
    }

    public function showReviews($placeID) {
        
        $apiKey = $this->settings['apiKey'];
        $lang = $this->settings['lang'];

        $xmlfile = 'https://maps.googleapis.com/maps/api/place/details/xml?placeid='.$placeID.'&fields=reviews&key='.$apiKey.'&language='.$lang;
        $xml = simplexml_load_file($xmlfile);
        $check = $xml->status[0];
        if ($check=='INVALID_REQUEST') {
            $output = "Error! Place ID or API Key not correct.";
        } else {
            $reviews = $xml->result[0];
            $output = array();
            for($i=0; $i < count($reviews); $i++) {
                $rating = substr((string) $xml->result[0]->review[$i]->rating[0], 0, 1);
                $ratingStars = array();
                $diffStars = array();
                for($r=0; $r < intval($rating); $r++) {
                    $ratingArray = array('star' => 1);
                    array_push($ratingStars, $ratingArray);
                }
                $starDiff = 5-intval($rating);
                for($s=0; $s < intval($starDiff); $s++) {
                    $starDiffArray = array('star' => 1);
                    array_push($diffStars, $starDiffArray);
                }
                $author_name = (string) $xml->result[0]->review[$i]->author_name[0];
                $content = array('text' => (string) $xml->result[0]->review[$i]->text[0], 'rating' => $ratingStars, 'stardiff' => $diffStars, 'profile_photo_url' => (string) $xml->result[0]->review[$i]->profile_photo_url[0], 'author_name' => $author_name, 'relative_time_description' => (string) $xml->result[0]->review[$i]->relative_time_description[0]);
                array_push($output, $content);
            }
        }

        return $output;

    }
}