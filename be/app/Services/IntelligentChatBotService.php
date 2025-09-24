<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\Category;

class IntelligentChatBotService
{
    private $openaiApiKey;
    private $contextService;
    private $knowledgeBase;

    public function __construct(ConversationContextService $contextService)
    {
        $this->openaiApiKey = env('OPENAI_API_KEY');
        $this->contextService = $contextService;
        $this->loadKnowledgeBase();
    }

    private function loadKnowledgeBase()
    {
        $this->knowledgeBase = [
            'company_info' => [
                'name' => 'Vicnex',
                'description' => 'Cửa hàng cầu lông chuyên nghiệp hàng đầu Việt Nam',
                'services' => [
                    'Bán vợt cầu lông chính hãng',
                    'Giày cầu lông chuyên nghiệp', 
                    'Trang phục thể thao',
                    'Đặt sân cầu lông trực tuyến',
                    'Phụ kiện và bảo dưỡng vợt'
                ],
                'brands' => ['Yonex', 'Lining', 'Victor', 'Mizuno', 'Apacs'],
                'locations' => 'Có nhiều chi nhánh tại TP.HCM và Hà Nội'
            ],
            'badminton_knowledge' => [
                'racket_types' => [
                    'Attack (Tấn công)' => 'Head-heavy, thân cứng, phù hợp smash mạnh',
                    'Defense (Phòng thủ)' => 'Head-light, thân mềm, phản xạ nhanh', 
                    'Control (Kiểm soát)' => 'Cân bằng, đa năng, chính xác cao'
                ],
                'skill_levels' => [
                    'Người mới' => 'Vợt nhẹ, giá phải chăng, dễ điều khiển',
                    'Trung bình' => 'Vợt cân bằng, chất lượng tốt',
                    'Chuyên nghiệp' => 'Vợt cao cấp, công nghệ tiên tiến'
                ],
                'brands_info' => [
                    'Yonex' => 'Thương hiệu số 1 thế giới, công nghệ Namd, Nanometric',
                    'Lining' => 'Thương hiệu Trung Quốc chất lượng cao, giá cạnh tranh',
                    'Victor' => 'Thương hiệu Đài Loan, công nghệ Nano Tec'
                ]
            ]
        ];
    }

    public function processMessage($question, $sessionId = 'default')
    {
        try {
            // Lấy context cuộc hội thoại
            $context = $this->contextService->getContext($sessionId);
            $conversationHistory = $this->buildConversationHistory($context);
            
            // Tạo system prompt với knowledge base
            $systemPrompt = $this->buildSystemPrompt();
            
            // Gọi OpenAI API
            $aiResponse = $this->callOpenAI($systemPrompt, $conversationHistory, $question);
            
            // Phân tích xem có cần recommend sản phẩm không
            $products = $this->extractProductRecommendations($aiResponse, $question);
            
            // Lưu context
            $this->contextService->storeContext($sessionId, 'ai_conversation', [
                'question' => $question,
                'response' => $aiResponse
            ]);
            
            return [
                'answer' => $aiResponse,
                'products' => $products,
                'intent' => $this->detectIntent($question),
                'session_id' => $sessionId
            ];
            
        } catch (\Exception $e) {
            Log::error('Intelligent ChatBot error: ' . $e->getMessage());
            
            // Fallback to rule-based response
            return $this->getFallbackResponse($question, $sessionId);
        }
    }

    private function buildSystemPrompt()
    {
        $company = $this->knowledgeBase['company_info'];
        $knowledge = $this->knowledgeBase['badminton_knowledge'];
        
        return "Bạn là trợ lý bán hàng chuyên nghiệp của {$company['name']} - {$company['description']}.

🏢 **Thông tin công ty:**
- Dịch vụ: " . implode(', ', $company['services']) . "
- Thương hiệu: " . implode(', ', $company['brands']) . "
- Địa điểm: {$company['locations']}

🏸 **Kiến thức cầu lông:**
- Loại vợt: Tấn công (head-heavy, cứng), Phòng thủ (head-light, mềm), Kiểm soát (cân bằng)
- Trình độ: Mới học (vợt nhẹ, rẻ), Trung bình (cân bằng), Chuyên nghiệp (cao cấp)
- Thương hiệu: Yonex (số 1 thế giới), Lining (chất lượng cao), Victor (công nghệ)

🎯 **Nhiệm vụ của bạn:**
1. Trả lời tất cả câu hỏi một cách thông minh và hữu ích
2. Tư vấn sản phẩm phù hợp với nhu cầu khách hàng  
3. Hướng dẫn về kỹ thuật, luật chơi cầu lông
4. Giải đáp thắc mắc về dịch vụ, chính sách
5. Tạo không khí thân thiện, chuyên nghiệp

📋 **Phong cách trả lời:**
- Thân thiện, nhiệt tình, chuyên nghiệp
- Sử dụng emoji phù hợp
- Đưa ra lời khuyên cụ thể, thực tế
- Khi không biết, hãy thành thật và gợi ý liên hệ hotline
- Luôn hướng khách hàng đến sản phẩm/dịch vụ phù hợp

Hãy trả lời mọi câu hỏi một cách tự nhiên và hữu ích nhất có thể!";
    }

    private function buildConversationHistory($context)
    {
        if (!$context || empty($context['history'])) {
            return [];
        }
        
        $messages = [];
        $recentHistory = array_slice($context['history'], -5); // Lấy 5 lượt cuối
        
        foreach ($recentHistory as $item) {
            if (isset($item['data']['question']) && isset($item['data']['response'])) {
                $messages[] = ['role' => 'user', 'content' => $item['data']['question']];
                $messages[] = ['role' => 'assistant', 'content' => $item['data']['response']];
            }
        }
        
        return $messages;
    }

    private function callOpenAI($systemPrompt, $conversationHistory, $question)
    {
        if (!$this->openaiApiKey) {
            throw new \Exception('OpenAI API key not configured');
        }

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt]
        ];
        
        // Thêm lịch sử hội thoại
        $messages = array_merge($messages, $conversationHistory);
        
        // Thêm câu hỏi hiện tại
        $messages[] = ['role' => 'user', 'content' => $question];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->openaiApiKey,
            'Content-Type' => 'application/json',
        ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => $messages,
            'max_tokens' => 500,
            'temperature' => 0.7,
            'frequency_penalty' => 0.3,
            'presence_penalty' => 0.3
        ]);

        if (!$response->successful()) {
            throw new \Exception('OpenAI API call failed: ' . $response->body());
        }

        $data = $response->json();
        return $data['choices'][0]['message']['content'] ?? 'Xin lỗi, tôi không thể trả lời câu hỏi này lúc này.';
    }

    private function extractProductRecommendations($aiResponse, $question)
    {
        $question = strtolower($question);
        $products = collect();

        // Phân tích từ khóa để recommend sản phẩm
        if (str_contains($question, 'vợt') || str_contains($aiResponse, 'vợt')) {
            $products = Product::where('Categories_ID', 1)
                ->where('Status', 1)
                ->orderBy('Price', 'desc')
                ->take(3)
                ->get();
        } elseif (str_contains($question, 'giày') || str_contains($aiResponse, 'giày')) {
            $products = Product::where('Categories_ID', 2)
                ->where('Status', 1)
                ->orderBy('Price', 'desc')
                ->take(3)
                ->get();
        } elseif (str_contains($question, 'áo') || str_contains($question, 'quần') || 
                  str_contains($aiResponse, 'trang phục')) {
            $products = Product::where('Categories_ID', 3)
                ->where('Status', 1)
                ->take(3)
                ->get();
        } elseif (str_contains($question, 'đắt') || str_contains($question, 'cao cấp')) {
            $products = Product::where('Status', 1)
                ->orderBy('Price', 'desc')
                ->take(3)
                ->get();
        } elseif (str_contains($question, 'rẻ') || str_contains($question, 'giá tốt')) {
            $products = Product::where('Status', 1)
                ->orderBy('Price', 'asc')
                ->take(3)
                ->get();
        }

        // Format products
        return $products->map(function ($product) {
            return [
                'id' => $product->Product_ID,
                'name' => $product->Name,
                'price' => $product->Discount_price ?: $product->Price,
                'original_price' => $product->Price,
                'image' => $this->getImageUrl($product->Image),
                'brand' => $product->Brand,
                'description' => $product->Description ? substr($product->Description, 0, 100) . '...' : 'Sản phẩm chất lượng cao'
            ];
        });
    }

    private function detectIntent($question)
    {
        $question = strtolower($question);
        
        if (str_contains($question, 'vợt')) return 'racket_inquiry';
        if (str_contains($question, 'giày')) return 'shoes_inquiry';
        if (str_contains($question, 'đặt sân')) return 'court_booking';
        if (str_contains($question, 'giá')) return 'price_inquiry';
        if (str_contains($question, 'thương hiệu')) return 'brand_inquiry';
        
        return 'general_conversation';
    }

    private function getFallbackResponse($question, $sessionId)
    {
        $fallbackResponses = [
            "Câu hỏi thú vị đấy! Tuy tôi chưa hiểu rõ lắm, nhưng tôi có thể giúp bạn tư vấn về sản phẩm cầu lông. Bạn cần tư vấn gì cụ thể không?",
            
            "Hmm, để tôi suy nghĩ... Bạn có thể hỏi tôi về vợt, giày, trang phục cầu lông, hoặc đặt sân. Tôi sẽ tư vấn chi tiết cho bạn!",
            
            "Câu hỏi hay đấy! Tôi có thể không hiểu hết, nhưng về cầu lông thì tôi biết rất nhiều. Bạn muốn tìm hiểu về sản phẩm nào không?"
        ];

        return [
            'answer' => $fallbackResponses[array_rand($fallbackResponses)],
            'products' => $this->getRandomProducts(),
            'intent' => 'fallback',
            'session_id' => $sessionId
        ];
    }

    private function getRandomProducts()
    {
        $products = Product::where('Status', 1)
            ->inRandomOrder()
            ->take(3)
            ->get();
            
        return $products->map(function ($product) {
            return [
                'id' => $product->Product_ID,
                'name' => $product->Name,
                'price' => $product->Discount_price ?: $product->Price,
                'original_price' => $product->Price,
                'image' => $this->getImageUrl($product->Image),
                'brand' => $product->Brand,
                'description' => $product->Description ? substr($product->Description, 0, 100) . '...' : 'Sản phẩm chất lượng cao'
            ];
        });
    }

    private function getImageUrl($image)
    {
        if (!$image) return '/no-image.png';
        if (strpos($image, 'http') === 0) return $image;
        if (strpos($image, 'uploads/') === 0) return "http://localhost:8000/$image";
        return "http://localhost:8000/uploads/products/$image";
    }
}