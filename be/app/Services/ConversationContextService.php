<?php

namespace App\Services;

class ConversationContextService
{
    private $contexts = [];
    
    public function storeContext($sessionId, $intent, $data = [])
    {
        if (!isset($this->contexts[$sessionId])) {
            $this->contexts[$sessionId] = [
                'history' => [],
                'preferences' => [],
                'current_intent' => null,
                'last_activity' => time()
            ];
        }
        
        $this->contexts[$sessionId]['history'][] = [
            'intent' => $intent,
            'data' => $data,
            'timestamp' => time()
        ];
        
        $this->contexts[$sessionId]['current_intent'] = $intent;
        $this->contexts[$sessionId]['last_activity'] = time();
        
        // Lưu preferences từ cuộc hội thoại
        $this->extractPreferences($sessionId, $intent, $data);
        
        // Giới hạn lịch sử (chỉ giữ 10 lượt cuối)
        if (count($this->contexts[$sessionId]['history']) > 10) {
            array_shift($this->contexts[$sessionId]['history']);
        }
    }
    
    public function getContext($sessionId)
    {
        return $this->contexts[$sessionId] ?? null;
    }
    
    public function getPreferences($sessionId)
    {
        return $this->contexts[$sessionId]['preferences'] ?? [];
    }
    
    private function extractPreferences($sessionId, $intent, $data)
    {
        $preferences = &$this->contexts[$sessionId]['preferences'];
        
        // Trích xuất thông tin từ intent
        switch (true) {
            case strpos($intent, 'skill_') === 0:
                $preferences['skill_level'] = str_replace('skill_', '', $intent);
                break;
                
            case strpos($intent, 'style_') === 0:
                $preferences['playing_style'] = str_replace('style_', '', $intent);
                break;
                
            case strpos($intent, 'budget_') === 0:
                $preferences['budget'] = intval(str_replace('budget_', '', $intent));
                break;
                
            case strpos($intent, 'brand_') === 0:
                $preferences['preferred_brand'] = str_replace('brand_', '', $intent);
                break;
                
            case strpos($intent, 'product_category_') === 0:
                $preferences['interested_category'] = str_replace('product_category_', '', $intent);
                break;
        }
    }
    
    public function generateContextualResponse($sessionId, $baseResponse)
    {
        $context = $this->getContext($sessionId);
        if (!$context) return $baseResponse;
        
        $preferences = $context['preferences'];
        $additions = [];
        
        // Thêm thông tin cá nhân hóa dựa trên preferences
        if (isset($preferences['skill_level'])) {
            $skill = $preferences['skill_level'];
            switch ($skill) {
                case 'beginner':
                    $additions[] = "\n💡 Tip cho người mới: Tập trung vào cách cầm vợt và di chuyển chân trước khi đầu tư vợt đắt tiền.";
                    break;
                case 'intermediate':
                    $additions[] = "\n💡 Tip cho người chơi trung bình: Hãy thử nhiều loại vợt khác nhau để tìm ra phong cách phù hợp.";
                    break;
                case 'advanced':
                    $additions[] = "\n💡 Tip cho người chơi giỏi: Xem xét công nghệ mới như Namd của Yonex để tăng hiệu suất.";
                    break;
            }
        }
        
        if (isset($preferences['budget'])) {
            $budget = $preferences['budget'];
            if ($budget < 150000) {
                $additions[] = "\n💰 Với ngân sách của bạn, tôi khuyên nên mua vợt mới thay vì vợt cũ để đảm bảo chất lượng.";
            } elseif ($budget > 300000) {
                $additions[] = "\n💰 Với ngân sách này bạn có thể chọn những dòng vợt cao cấp với công nghệ tiên tiến nhất.";
            }
        }
        
        // Thêm cross-sell suggestions
        if (isset($preferences['interested_category'])) {
            $catId = $preferences['interested_category'];
            if ($catId == 1) { // Vợt
                $additions[] = "\n🎯 Gợi ý: Bạn có muốn xem thêm dây vợt và grip phù hợp không?";
            } elseif ($catId == 2) { // Giày
                $additions[] = "\n🎯 Gợi ý: Đừng quên tất cầu lông chuyên dụng để bảo vệ chân tốt hơn nhé!";
            }
        }
        
        return $baseResponse . implode('', $additions);
    }
    
    public function isReturningCustomer($sessionId)
    {
        $context = $this->getContext($sessionId);
        return $context && count($context['history']) > 3;
    }
    
    public function getPersonalizedGreeting($sessionId)
    {
        if ($this->isReturningCustomer($sessionId)) {
            $preferences = $this->getPreferences($sessionId);
            $skill = $preferences['skill_level'] ?? 'unknown';
            
            switch ($skill) {
                case 'beginner':
                    return "Chào bạn trở lại! Hy vọng bạn đã có những buổi tập thú vị. Cần tư vấn thêm gì không?";
                case 'intermediate':
                    return "Xin chào! Bạn đã lâu rồi không ghé thăm. Có sản phẩm nào mới bạn muốn tìm hiểu không?";
                case 'advanced':
                    return "Chào anh/chị! Rất vui được gặp lại. Có gear nào mới cần upgrade không?";
                default:
                    return "Chào bạn quay lại! Tôi vẫn nhớ những sản phẩm bạn quan tâm. Cần hỗ trợ gì hôm nay?";
            }
        }
        
        return null; // Sử dụng greeting mặc định
    }
    
    public function cleanupOldContexts($maxAge = 3600) // 1 giờ
    {
        $currentTime = time();
        foreach ($this->contexts as $sessionId => $context) {
            if ($currentTime - $context['last_activity'] > $maxAge) {
                unset($this->contexts[$sessionId]);
            }
        }
    }
}