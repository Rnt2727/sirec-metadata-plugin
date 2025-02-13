<?php
class ERM_Evaluator {
    private $criteria;
    
    public function __construct() {
        $this->init_criteria();
    }
    
    private function init_criteria() {
        $this->criteria = array(
            'consultation' => array(
                'relevance' => 20,
                'quality' => 20,
                'usability' => 20,
                'accessibility' => 20,
                'technical' => 20
            ),
            'teacher_support' => array(
                'pedagogical' => 25,
                'content' => 25,
                'design' => 25,
                'technical' => 25
            )
        );
    }
    
    public function evaluate_resource($resource_id, $evaluation_data) {
        $score = $this->calculate_score($evaluation_data);
        return $this->determine_seal($score);
    }
    
    private function calculate_score($evaluation_data) {
        // Implementación básica de cálculo de puntuación
        return array_sum($evaluation_data) / count($evaluation_data);
    }
    
    private function determine_seal($score) {
        if ($score >= 91 && $score <= 94) return 'advanced';
        if ($score >= 95 && $score <= 99) return 'superior';
        if ($score == 100) return 'excellence';
        return null;
    }
}