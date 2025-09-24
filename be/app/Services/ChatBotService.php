<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class ChatBotService
{
    private $trainingData;
    private $productKnowledge;
    private $categories;
    private $brands;
    private $contextService;

    public function __construct(ConversationContextService $contextService)
    {
        $this->contextService = $contextService;
        $this->loadTrainingData();
        $this->loadProductKnowledge();
    }

    private function loadTrainingData()
    {
        $this->trainingData = [
            // Chào hỏi và giới thiệu
            'greeting' => [
                'patterns' => ['xin chào', 'hello', 'hi', 'chào bạn', 'hey', 'chào'],
                'responses' => [
                    "Xin chào! Tôi là trợ lý bán hàng chuyên nghiệp của Vicnex - chuyên cung cấp đồ cầu lông chính hãng. Tôi có thể giúp bạn:",
                    "🏸 Tư vấn vợt phù hợp với phong cách chơi",
                    "👟 Chọn giày cầu lông phù hợp",
                    "👕 Tư vấn trang phục thể thao",
                    "🏟️ Đặt sân cầu lông",
                    "💰 Tìm sản phẩm theo ngân sách",
                    "\nBạn cần tư vấn gì hôm nay?"
                ]
            ],

            // Tư vấn mua vợt - kịch bản bán hàng chuyên nghiệp
            'buy_racket' => [
                'patterns' => ['muốn mua vợt', 'mua vợt', 'cần vợt', 'tìm vợt', 'vợt nào', 'chọn vợt'],
                'consultation_questions' => [
                    "Tuyệt vời! Tôi sẽ giúp bạn chọn cây vợt phù hợp nhất. 🏸",
                    "",
                    "Để tư vấn chính xác, cho tôi biết:",
                    "🎯 **Trình độ hiện tại:** Bạn chơi cầu lông bao lâu rồi?",
                    "💰 **Ngân sách:** Bạn dự kiến chi khoảng bao nhiêu?", 
                    "🏃 **Phong cách chơi:** Bạn thích tấn công, phòng thủ hay đa năng?",
                    "🏆 **Mục đích:** Chơi giải trí, tập luyện hay thi đấu?",
                    "",
                    "Hoặc bạn có thể trả lời ngắn gọn như:",
                    "• 'Tôi mới học, ngân sách 100k'",
                    "• 'Chơi được 2 năm, thích smash, budget 300k'",
                    "• 'Chơi lâu rồi, cần vợt thi đấu'"
                ]
            ],

            // Tư vấn theo trình độ
            'skill_level' => [
                'beginner' => [
                    'patterns' => ['mới học', 'người mới', 'bắt đầu', 'beginner', 'tập chơi', 'học đánh'],
                    'characteristics' => 'Vợt nhẹ, thân mềm, head-light, giá phải chăng',
                    'recommended_brands' => ['Yonex Muscle Power', 'Yonex Carbonex', 'Lining XP'],
                    'price_range' => [50000, 150000],
                    'advice' => "Cho người mới bắt đầu, tôi khuyên bạn chọn vợt có đặc điểm:\n- Trọng lượng nhẹ (85-90g)\n- Thân vợt mềm để dễ điều khiển\n- Head-light giúp xoay vợt nhanh\n- Giá cả phải chăng để trải nghiệm"
                ],
                'intermediate' => [
                    'patterns' => ['trung bình', 'đã biết chơi', 'intermediate', 'chơi được', 'tạm ổn'],
                    'characteristics' => 'Cân bằng, độ cứng medium, đa năng',
                    'recommended_brands' => ['Yonex Arcsaber', 'Lining Aeronaut', 'Victor Jetspeed'],
                    'price_range' => [150000, 300000],
                    'advice' => "Với trình độ trung bình, bạn nên chọn:\n- Vợt cân bằng hoặc slightly head-heavy\n- Độ cứng thân medium\n- Có thể chơi được cả đơn và đôi\n- Chất lượng tốt để cải thiện kỹ thuật"
                ],
                'advanced' => [
                    'patterns' => ['chuyên nghiệp', 'pro', 'giỏi', 'advanced', 'thi đấu', 'chơi lâu năm'],
                    'characteristics' => 'Head-heavy, thân cứng, công nghệ cao',
                    'recommended_brands' => ['Yonex Astrox', 'Lining Aeronaut', 'Victor Thruster'],
                    'price_range' => [250000, 500000],
                    'advice' => "Cho người chơi giỏi, tôi gợi ý:\n- Vợt head-heavy cho power mạnh\n- Thân cứng để kiểm soát chính xác\n- Công nghệ tiên tiến như Namd, Nanometric\n- Chất lượng cao cho thi đấu chuyên nghiệp"
                ]
            ],

            // Tư vấn theo phong cách chơi
            'playing_style' => [
                'attack' => [
                    'patterns' => ['tấn công', 'smash', 'đập bóng', 'sức mạnh', 'power'],
                    'recommendations' => 'Head-heavy, thân cứng, Astrox series',
                    'advice' => "Phong cách tấn công cần:\n- Vợt head-heavy để tăng power smash\n- Thân cứng để truyền lực tốt\n- Công nghệ tăng tốc độ vợt như Rotational Generator System"
                ],
                'defense' => [
                    'patterns' => ['phòng thủ', 'đỡ bóng', 'phản tạt', 'defense', 'chặn'],
                    'recommendations' => 'Head-light, thân mềm, Nanoflare series',
                    'advice' => "Phong cách phòng thủ cần:\n- Vợt nhẹ, head-light để phản xạ nhanh\n- Thân mềm để dễ điều khiển\n- Thiết kế aerodynamic giảm lực cản"
                ],
                'control' => [
                    'patterns' => ['kiểm soát', 'control', 'chính xác', 'kỹ thuật', 'đa năng'],
                    'recommendations' => 'Cân bằng, medium flex, Arcsaber series',
                    'advice' => "Phong cách kiểm soát cần:\n- Vợt cân bằng hoặc slightly head-light\n- Độ cứng thân medium\n- Thiết kế ổn định để đánh chính xác"
                ]
            ],

            // Tư vấn ngân sách
            'budget' => [
                'low' => [
                    'range' => [0, 150000],
                    'advice' => "Với ngân sách dưới 150k, tôi gợi ý các sản phẩm tốt:\n- Dòng Muscle Power, Carbonex của Yonex\n- Dòng XP của Lining\n- Chất lượng tốt cho người mới học"
                ],
                'medium' => [
                    'range' => [150000, 300000],
                    'advice' => "Ngân sách 150k-300k có nhiều lựa chọn tốt:\n- Yonex Arcsaber, Nanoflare\n- Lining Aeronaut, Windstorm\n- Victor Jetspeed\n- Chất lượng và hiệu suất cân bằng"
                ],
                'high' => [
                    'range' => [300000, 1000000],
                    'advice' => "Với ngân sách cao, bạn có thể chọn:\n- Yonex Astrox flagship series\n- Lining Aeronaut high-end\n- Victor Thruster\n- Công nghệ tiên tiến nhất, chất lượng tuyệt đỉnh"
                ]
            ],

            // Tư vấn giày cầu lông
            'shoes' => [
                'patterns' => ['giày', 'shoes', 'dép', 'footwear'],
                'advice' => "Giày cầu lông cần có:\n- Đế chống trượt tốt\n- Hỗ trợ cổ chân\n- Đệm giảm chấn\n- Thoáng khí\n- Thương hiệu uy tín: Yonex, Lining, Victor",
                'recommendations' => [
                    'Yonex Power Cushion - đệm giảm chấn tuyệt vời',
                    'Lining Ranger - grip tốt, bền bỉ',
                    'Victor Professional - thiết kế chuyên nghiệp'
                ]
            ],

            // Tư vấn trang phục
            'apparel' => [
                'patterns' => ['áo', 'quần', 'trang phục', 'apparel', 'clothes', 'đồ'],
                'advice' => "Trang phục cầu lông chất lượng:\n- Vải thấm hút mồ hôi tốt\n- Co giãn 4 chiều\n- Thoáng mát\n- Thiết kế thể thao\n- Thương hiệu: Yonex, Lining, Victor",
                'types' => [
                    'Áo đấu - thiết kế chuyên nghiệp',
                    'Quần short - thoải mái vận động',
                    'Váy tennis - cho nữ vận động viên'
                ]
            ],

            // Kịch bản tư vấn theo ngân sách
            'budget_consultation' => [
                'patterns' => ['bao nhiêu tiền', 'giá bao nhiêu', 'chi phí', 'ngân sách', 'budget'],
                'response' => [
                    "Tôi hiểu bạn quan tâm đến giá cả! 💰",
                    "",
                    "**Phân khúc giá vợt cầu lông:**",
                    "🥉 **Phổ thông (50k-150k):** Phù hợp người mới, chất lượng ổn định",
                    "🥈 **Trung cấp (150k-300k):** Chất lượng tốt, đa dạng tính năng", 
                    "🥇 **Cao cấp (300k-500k+):** Công nghệ tiên tiến, hiệu suất tối ưu",
                    "",
                    "Bạn muốn xem sản phẩm ở phân khúc nào? Hoặc cho tôi biết ngân sách cụ thể để tư vấn chính xác hơn!"
                ]
            ],

            // Tư vấn thương hiệu
            'brand_consultation' => [
                'patterns' => ['thương hiệu nào', 'hãng nào', 'brand nào', 'yonex hay lining', 'so sánh thương hiệu'],
                'response' => [
                    "Câu hỏi rất hay! Mỗi thương hiệu có điểm mạnh riêng: 🏆",
                    "",
                    "🇯🇵 **YONEX** - Vua của cầu lông:",
                    "• Chất lượng hàng đầu thế giới, được VĐV Olympic tin dùng",
                    "• Công nghệ tiên tiến: Namd, Nanometric, Isometric",
                    "• Phù hợp: Người chơi từ trung cấp đến chuyên nghiệp",
                    "",
                    "🇨🇳 **LINING** - Tỷ lệ giá/chất lượng tốt:",
                    "• Công nghệ Dynamic-Optimum Frame, TB Nano",
                    "• Giá cạnh tranh, chất lượng ổn định",
                    "• Phù hợp: Mọi đối tượng, đặc biệt người mới bắt đầu",
                    "",
                    "🇹🇼 **VICTOR** - Thiết kế và công nghệ:",
                    "• Nổi tiếng về aerodynamics và frame innovation",
                    "• Thiết kế đẹp, cầm chắc tay",
                    "• Phù hợp: Người thích tấn công và kiểm soát",
                    "",
                    "Bạn có ưu tiên thương hiệu nào không?"
                ]
            ],

            // Đặt sân
            'court_booking' => [
                'patterns' => ['đặt sân', 'booking', 'sân', 'court', 'thuê sân'],
                'advice' => "Dịch vụ đặt sân của Vicnex:\n- Hệ thống sân chất lượng cao\n- Đặt online 24/7\n- Giá cạnh tranh\n- Vị trí thuận lợi\n- Trang thiết bị hiện đại",
                'process' => [
                    '1. Chọn sân và thời gian',
                    '2. Đăng nhập tài khoản',
                    '3. Xác nhận thông tin',
                    '4. Thanh toán online',
                    '5. Nhận xác nhận đặt sân'
                ]
            ],

            // Chăm sóc sản phẩm
            'maintenance' => [
                'patterns' => ['bảo quản', 'chăm sóc', 'maintenance', 'giữ gìn'],
                'racket_care' => [
                    "Cách bảo quản vợt cầu lông:",
                    "🎯 Không để vợt ở nhiệt độ cao",
                    "🎯 Nới căng dây khi không dùng",
                    "🎯 Sử dụng bao vợt khi mang theo",
                    "🎯 Kiểm tra và thay dây định kỳ",
                    "🎯 Vệ sinh vợt sau khi chơi"
                ]
            ],

            // Thông tin thương hiệu
            'brand_info' => [
                'yonex' => "Yonex - thương hiệu số 1 thế giới về cầu lông, được các VĐV hàng đầu tin dùng",
                'lining' => "Lining - thương hiệu Trung Quốc hàng đầu, chất lượng cao, giá cạnh tranh",
                'victor' => "Victor - thương hiệu Đài Loan uy tín, chuyên về công nghệ và thiết kế"
            ]
        ];
    }

    private function loadProductKnowledge()
    {
        // Lấy thông tin categories
        $this->categories = [
            1 => ['name' => 'Vợt cầu lông', 'keywords' => ['vợt', 'racket', 'raket']],
            2 => ['name' => 'Giày cầu lông', 'keywords' => ['giày', 'shoes', 'dép']],
            3 => ['name' => 'Quần áo cầu lông', 'keywords' => ['áo', 'quần', 'trang phục', 'apparel']],
            4 => ['name' => 'Phụ kiện cầu lông', 'keywords' => ['phụ kiện', 'accessories', 'bao', 'dây']],
            5 => ['name' => 'Cầu lông', 'keywords' => ['cầu', 'shuttlecock', 'birdie']]
        ];

        // Lấy thông tin brands
        $this->brands = [
            'Yonex' => ['description' => 'Thương hiệu số 1 thế giới', 'origin' => 'Nhật Bản'],
            'Lining' => ['description' => 'Thương hiệu hàng đầu châu Á', 'origin' => 'Trung Quốc'],  
            'Victor' => ['description' => 'Thương hiệu công nghệ cao', 'origin' => 'Đài Loan'],
            'Mizuno' => ['description' => 'Thương hiệu thể thao uy tín', 'origin' => 'Nhật Bản'],
            'Apacs' => ['description' => 'Thương hiệu Malaysia chất lượng', 'origin' => 'Malaysia']
        ];
    }

    public function processMessage($question, $sessionId = 'default')
    {
        $question = strtolower(trim($question));
        
        // Kiểm tra nếu là khách hàng quay lại
        $personalizedGreeting = $this->contextService->getPersonalizedGreeting($sessionId);
        
        // Phân tích intent
        $intent = $this->analyzeIntent($question);
        
        // Lưu context
        $this->contextService->storeContext($sessionId, $intent, ['question' => $question]);
        
        // Tạo response dựa trên intent
        $baseResponse = $this->generateResponse($intent, $question, $sessionId);
        
        // Nếu có greeting cá nhân hóa và đây là greeting intent
        if ($personalizedGreeting && $intent === 'greeting') {
            $baseResponse = $personalizedGreeting;
        }
        
        // Thêm context để cá nhân hóa response
        $response = $this->contextService->generateContextualResponse($sessionId, $baseResponse);
        
        // Lấy sản phẩm gợi ý dựa trên context và intent
        $products = $this->getRecommendedProducts($intent, $question, $sessionId);
        
        return [
            'answer' => $response,
            'products' => $products,
            'intent' => $intent,
            'session_id' => $sessionId
        ];
    }

    private function analyzeIntent($question)
    {
        // Kiểm tra chào hỏi
        foreach ($this->trainingData['greeting']['patterns'] as $pattern) {
            if (strpos($question, $pattern) !== false) {
                return 'greeting';
            }
        }

        // Kiểm tra ý định mua vợt - ưu tiên cao
        foreach ($this->trainingData['buy_racket']['patterns'] as $pattern) {
            if (strpos($question, $pattern) !== false) {
                return 'buy_racket';
            }
        }

        // Kiểm tra trình độ
        foreach ($this->trainingData['skill_level'] as $level => $data) {
            foreach ($data['patterns'] as $pattern) {
                if (strpos($question, $pattern) !== false) {
                    return "skill_$level";
                }
            }
        }

        // Kiểm tra phong cách chơi
        foreach ($this->trainingData['playing_style'] as $style => $data) {
            foreach ($data['patterns'] as $pattern) {
                if (strpos($question, $pattern) !== false) {
                    return "style_$style";
                }
            }
        }

        // Kiểm tra sản phẩm
        foreach ($this->categories as $catId => $catData) {
            foreach ($catData['keywords'] as $keyword) {
                if (strpos($question, $keyword) !== false) {
                    return "product_category_$catId";
                }
            }
        }

        // Kiểm tra thương hiệu
        foreach ($this->brands as $brand => $info) {
            if (strpos($question, strtolower($brand)) !== false) {
                return "brand_" . strtolower($brand);
            }
        }

        // Kiểm tra câu hỏi về sản phẩm đắt nhất
        if ((str_contains($question, 'đắt nhất') || str_contains($question, 'cao nhất') || 
             str_contains($question, 'expensive') || str_contains($question, 'premium')) &&
            (str_contains($question, 'sản phẩm') || str_contains($question, 'vợt') || 
             str_contains($question, 'giày') || str_contains($question, 'product'))) {
            return 'most_expensive';
        }

        // Kiểm tra câu hỏi về sản phẩm rẻ nhất
        if ((str_contains($question, 'rẻ nhất') || str_contains($question, 'thấp nhất') || 
             str_contains($question, 'cheapest') || str_contains($question, 'budget')) &&
            (str_contains($question, 'sản phẩm') || str_contains($question, 'vợt') || 
             str_contains($question, 'giày') || str_contains($question, 'product'))) {
            return 'cheapest';
        }

        // Kiểm tra câu hỏi về bán chạy nhất
        if (str_contains($question, 'bán chạy') || str_contains($question, 'phổ biến') || 
            str_contains($question, 'bestseller') || str_contains($question, 'hot nhất') ||
            str_contains($question, 'nổi tiếng') || str_contains($question, 'được ưa chuộng')) {
            return 'bestseller';
        }

        // Kiểm tra tư vấn ngân sách
        foreach ($this->trainingData['budget_consultation']['patterns'] as $pattern) {
            if (strpos($question, $pattern) !== false) {
                return 'budget_consultation';
            }
        }

        // Kiểm tra tư vấn thương hiệu
        foreach ($this->trainingData['brand_consultation']['patterns'] as $pattern) {
            if (strpos($question, $pattern) !== false) {
                return 'brand_consultation';
            }
        }

        // Kiểm tra đặt sân
        foreach ($this->trainingData['court_booking']['patterns'] as $pattern) {
            if (strpos($question, $pattern) !== false) {
                return 'court_booking';
            }
        }

        // Kiểm tra ngân sách
        if (preg_match('/(\d+).*(?:k|000|đồng|vnđ)/', $question, $matches)) {
            $amount = intval($matches[1]);
            if (strpos($question, 'k') !== false && $amount < 1000) {
                $amount *= 1000;
            }
            return "budget_$amount";
        }

        // Phân tích câu trả lời kết hợp (ví dụ: "tôi mới học, budget 100k")
        $combinedIntent = $this->analyzeCombinedIntent($question);
        if ($combinedIntent) {
            return $combinedIntent;
        }

        return 'general_inquiry';
    }

    private function analyzeCombinedIntent($question)
    {
        $intents = [];
        
        // Kiểm tra trình độ
        foreach ($this->trainingData['skill_level'] as $level => $data) {
            foreach ($data['patterns'] as $pattern) {
                if (strpos($question, $pattern) !== false) {
                    $intents[] = "skill_$level";
                    break 2;
                }
            }
        }
        
        // Kiểm tra ngân sách
        if (preg_match('/(\d+).*(?:k|000|đồng|vnđ)/', $question, $matches)) {
            $amount = intval($matches[1]);
            if (strpos($question, 'k') !== false && $amount < 1000) {
                $amount *= 1000;
            }
            $intents[] = "budget_$amount";
        }
        
        // Kiểm tra phong cách
        foreach ($this->trainingData['playing_style'] as $style => $data) {
            foreach ($data['patterns'] as $pattern) {
                if (strpos($question, $pattern) !== false) {
                    $intents[] = "style_$style";
                    break 2;
                }
            }
        }
        
        // Nếu có nhiều intent, trả về combined
        if (count($intents) > 1) {
            return 'combined_' . implode('_', $intents);
        }
        
        return null;
    }

    private function generateResponse($intent, $question, $sessionId)
    {
        // Lấy context preferences để cá nhân hóa
        $preferences = $this->contextService->getPreferences($sessionId);
        
        switch (true) {
            case $intent === 'greeting':
                return implode("\n", $this->trainingData['greeting']['responses']);

            case $intent === 'buy_racket':
                return implode("\n", $this->trainingData['buy_racket']['consultation_questions']);

            case $intent === 'budget_consultation':
                return implode("\n", $this->trainingData['budget_consultation']['response']);

            case $intent === 'brand_consultation':
                return implode("\n", $this->trainingData['brand_consultation']['response']);

            case strpos($intent, 'skill_') === 0:
                $skill = str_replace('skill_', '', $intent);
                $data = $this->trainingData['skill_level'][$skill];
                return $data['advice'] . "\n\nTôi sẽ gợi ý một số sản phẩm phù hợp cho bạn:";

            case strpos($intent, 'style_') === 0:
                $style = str_replace('style_', '', $intent);
                $data = $this->trainingData['playing_style'][$style];
                return $data['advice'] . "\n\nCác sản phẩm phù hợp:";

            case strpos($intent, 'product_category_') === 0:
                $catId = str_replace('product_category_', '', $intent);
                $categoryName = $this->categories[$catId]['name'];
                return "Bạn đang quan tâm đến $categoryName. Đây là những sản phẩm chất lượng cao mà tôi gợi ý:";

            case strpos($intent, 'brand_') === 0:
                $brand = ucfirst(str_replace('brand_', '', $intent));
                if (isset($this->brands[$brand])) {
                    $info = $this->brands[$brand];
                    return "$brand - {$info['description']} từ {$info['origin']}. Đây là những sản phẩm $brand chất lượng:";
                }
                break;

            case $intent === 'court_booking':
                $response = $this->trainingData['court_booking']['advice'] . "\n\n🏸 **Quy trình đặt sân:**\n";
                $response .= implode("\n", $this->trainingData['court_booking']['process']);
                $response .= "\n\n💡 **Lưu ý:** Đặt sân trước 2-3 tiếng để đảm bảo có chỗ nhé!";
                return $response;

            case $intent === 'most_expensive':
                return "🏆 **Sản phẩm cao cấp nhất của Vicnex:**\n\n" .
                       "Đây là những sản phẩm premium với công nghệ tiên tiến nhất, được các VĐV chuyên nghiệp tin dùng:\n" .
                       "• Chất lượng tuyệt đỉnh\n" .
                       "• Công nghệ độc quyền\n" .
                       "• Bảo hành chính hãng\n\n" .
                       "Hãy xem những 'siêu phẩm' này:";

            case $intent === 'cheapest':
                return "💰 **Sản phẩm giá tốt nhất tại Vicnex:**\n\n" .
                       "Chúng tôi luôn có những sản phẩm chất lượng với giá cả phù hợp:\n" .
                       "• Chính hãng 100%\n" .
                       "• Chất lượng được đảm bảo\n" .
                       "• Phù hợp người mới bắt đầu\n\n" .
                       "Đây là những lựa chọn tốt nhất trong tầm giá:";

            case $intent === 'bestseller':
                return "🔥 **Sản phẩm bán chạy nhất tại Vicnex:**\n\n" .
                       "Đây là những sản phẩm được khách hàng yêu thích và mua nhiều nhất:\n" .
                       "• Được đánh giá cao\n" .
                       "• Tỷ lệ hài lòng 98%\n" .
                       "• Phù hợp nhiều đối tượng\n\n" .
                       "Top sản phẩm 'hot' nhất hiện tại:";

            case strpos($intent, 'budget_') === 0:
                $amount = intval(str_replace('budget_', '', $intent));
                if ($amount < 150000) {
                    return $this->trainingData['budget']['low']['advice'];
                } elseif ($amount < 300000) {
                    return $this->trainingData['budget']['medium']['advice'];
                } else {
                    return $this->trainingData['budget']['high']['advice'];
                }

            default:
                // Xử lý combined intent
                if (strpos($intent, 'combined_') === 0) {
                    return $this->handleCombinedIntent($intent, $preferences);
                }
                
                return "Tôi hiểu bạn cần tư vấn về cầu lông. Để tôi gợi ý chính xác hơn, bạn có thể cho tôi biết:\n" .
                       "🎯 Trình độ của bạn (mới học/trung bình/giỏi)\n" .
                       "🎯 Ngân sách dự kiến\n" .
                       "🎯 Phong cách chơi (tấn công/phòng thủ/kiểm soát)\n" .
                       "🎯 Loại sản phẩm cần (vợt/giày/áo quần)\n\n" .
                       "Hoặc bạn có thể xem một số sản phẩm nổi bật:";
        }
    }

    private function handleCombinedIntent($intent, $preferences)
    {
        $parts = explode('_', str_replace('combined_', '', $intent));
        $response = "Tuyệt vời! Dựa trên thông tin bạn cung cấp, tôi có những gợi ý sau:\n\n";
        
        $skill = null;
        $budget = null;
        $style = null;
        
        foreach ($parts as $part) {
            if (strpos($part, 'skill') === 0) {
                $skill = str_replace('skill', '', $part);
            }
            if (strpos($part, 'budget') === 0) {
                $budget = intval(str_replace('budget', '', $part));
            }
            if (strpos($part, 'style') === 0) {
                $style = str_replace('style', '', $part);
            }
        }
        
        // Tạo response dựa trên thông tin kết hợp
        if ($skill && $budget) {
            $response .= $this->getSkillBudgetAdvice($skill, $budget);
        }
        
        if ($style) {
            $response .= "\n\n🎯 **Phong cách " . ucfirst($style) . ":**\n";
            $response .= $this->trainingData['playing_style'][$style]['advice'];
        }
        
        $response .= "\n\n✨ **Gợi ý sản phẩm phù hợp nhất:**";
        
        return $response;
    }

    private function getSkillBudgetAdvice($skill, $budget)
    {
        $advice = "🎯 **Phân tích của tôi:**\n";
        
        if ($skill === 'beginner') {
            if ($budget < 100000) {
                $advice .= "• Ngân sách hơi ít cho người mới học\n";
                $advice .= "• Khuyên bạn nên đầu tư ít nhất 80-100k cho vợt đầu tiên\n";
                $advice .= "• Vợt quá rẻ có thể ảnh hưởng đến quá trình học";
            } elseif ($budget <= 150000) {
                $advice .= "• Ngân sách vừa phải cho người mới học\n";
                $advice .= "• Có thể chọn được vợt chất lượng tốt từ Yonex, Lining\n";
                $advice .= "• Đủ để sử dụng 1-2 năm đầu";
            } else {
                $advice .= "• Ngân sách thoải mái cho người mới\n";
                $advice .= "• Có thể chọn vợt cao cấp hơn để dùng lâu dài\n";
                $advice .= "• Khuyên chọn thương hiệu uy tín";
            }
        } elseif ($skill === 'intermediate') {
            if ($budget < 200000) {
                $advice .= "• Nên tăng ngân sách lên 200-300k để có vợt tốt hơn\n";
                $advice .= "• Ở trình độ này, vợt tốt sẽ giúp bạn tiến bộ nhanh hơn";
            } else {
                $advice .= "• Ngân sách phù hợp cho trình độ trung cấp\n";
                $advice .= "• Có thể chọn vợt chuyên biệt theo phong cách chơi\n";
                $advice .= "• Đầu tư đáng giá cho việc nâng cao kỹ năng";
            }
        } elseif ($skill === 'advanced') {
            if ($budget < 300000) {
                $advice .= "• Với trình độ cao, nên đầu tư vợt tốt hơn\n";
                $advice .= "• Vợt chất lượng cao sẽ phát huy tối đa khả năng";
            } else {
                $advice .= "• Ngân sách hoàn hảo cho người chơi giỏi\n";
                $advice .= "• Có thể chọn vợt cao cấp nhất với công nghệ tiên tiến\n";
                $advice .= "• Phù hợp cho thi đấu và luyện tập chuyên nghiệp";
            }
        }
        
        return $advice;
    }

    private function getProductsForCombinedIntent($intent, $query)
    {
        $parts = explode('_', str_replace('combined_', '', $intent));
        
        $skill = null;
        $budget = null;
        $style = null;
        
        foreach ($parts as $part) {
            if (strpos($part, 'skill') === 0) {
                $skill = str_replace('skill', '', $part);
            }
            if (strpos($part, 'budget') === 0) {
                $budget = intval(str_replace('budget', '', $part));
            }
            if (strpos($part, 'style') === 0) {
                $style = str_replace('style', '', $part);
            }
        }
        
        // Lọc sản phẩm theo ngân sách
        if ($budget) {
            if ($budget <= 150000) {
                $query = $query->where('Price', '<=', $budget + 20000); // Cho phép linh hoạt 20k
            } else {
                $query = $query->where('Price', '<=', $budget);
            }
        }
        
        // Lọc theo trình độ và phong cách
        if ($skill === 'beginner') {
            $query = $query->where(function($q) {
                $q->where('Name', 'LIKE', '%Muscle Power%')
                  ->orWhere('Name', 'LIKE', '%Carbonex%')
                  ->orWhere('Name', 'LIKE', '%XP%');
            });
        } elseif ($skill === 'intermediate') {
            $query = $query->where(function($q) {
                $q->where('Name', 'LIKE', '%Arcsaber%')
                  ->orWhere('Name', 'LIKE', '%Aeronaut%')
                  ->orWhere('Name', 'LIKE', '%Jetspeed%');
            });
        } elseif ($skill === 'advanced') {
            $query = $query->where(function($q) {
                $q->where('Name', 'LIKE', '%Astrox%')
                  ->orWhere('Name', 'LIKE', '%Thruster%')
                  ->orWhere('Name', 'LIKE', '%Pro%');
            });
        }
        
        // Lọc theo phong cách chơi
        if ($style === 'attack') {
            $query = $query->where(function($q) {
                $q->where('Name', 'LIKE', '%Astrox%')
                  ->orWhere('Name', 'LIKE', '%Thruster%')
                  ->orWhere('Name', 'LIKE', '%Power%');
            });
        } elseif ($style === 'defense') {
            $query = $query->where(function($q) {
                $q->where('Name', 'LIKE', '%Nanoflare%')
                  ->orWhere('Name', 'LIKE', '%Jetspeed%')
                  ->orWhere('Name', 'LIKE', '%Speed%');
            });
        } elseif ($style === 'control') {
            $query = $query->where(function($q) {
                $q->where('Name', 'LIKE', '%Arcsaber%')
                  ->orWhere('Name', 'LIKE', '%Aeronaut%')
                  ->orWhere('Name', 'LIKE', '%Control%');
            });
        }
        
        $products = $query->take(4)->get();
        
        // Nếu không có sản phẩm phù hợp, lấy sản phẩm gần đúng
        if ($products->isEmpty()) {
            $fallbackQuery = Product::where('Status', 1)->where('Categories_ID', 1);
            if ($budget) {
                $fallbackQuery = $fallbackQuery->where('Price', '<=', $budget * 1.2); // Cho phép vượt 20%
            }
            $products = $fallbackQuery->take(3)->get();
        }
        
        return $products;
    }

    private function getRecommendedProducts($intent, $question, $sessionId)
    {
        $query = Product::where('Status', 1);
        $preferences = $this->contextService->getPreferences($sessionId);
        
        // Lọc theo intent
        switch (true) {
            case strpos($intent, 'skill_beginner') === 0:
                $products = $query->where('Price', '<=', 150000)
                    ->whereIn('Brand', ['Yonex'])
                    ->where('Name', 'LIKE', '%Muscle Power%')
                    ->orWhere('Name', 'LIKE', '%Carbonex%')
                    ->take(3)->get();
                break;

            case strpos($intent, 'skill_intermediate') === 0:
                $products = $query->whereBetween('Price', [150000, 300000])
                    ->where('Name', 'LIKE', '%Arcsaber%')
                    ->orWhere('Name', 'LIKE', '%Aeronaut%')
                    ->take(3)->get();
                break;

            case strpos($intent, 'skill_advanced') === 0:
                $products = $query->where('Price', '>=', 250000)
                    ->where('Name', 'LIKE', '%Astrox%')
                    ->orWhere('Name', 'LIKE', '%Thruster%')
                    ->take(3)->get();
                break;

            case strpos($intent, 'style_attack') === 0:
                $products = $query->where('Name', 'LIKE', '%Astrox%')
                    ->orWhere('Name', 'LIKE', '%Thruster%')
                    ->take(3)->get();
                break;

            case strpos($intent, 'style_defense') === 0:
                $products = $query->where('Name', 'LIKE', '%Nanoflare%')
                    ->orWhere('Name', 'LIKE', '%Jetspeed%')
                    ->take(3)->get();
                break;

            case strpos($intent, 'style_control') === 0:
                $products = $query->where('Name', 'LIKE', '%Arcsaber%')
                    ->orWhere('Name', 'LIKE', '%Aeronaut%')
                    ->take(3)->get();
                break;

            case strpos($intent, 'product_category_') === 0:
                $catId = str_replace('product_category_', '', $intent);
                $products = $query->where('Categories_ID', $catId)->take(4)->get();
                break;

            case strpos($intent, 'brand_') === 0:
                $brand = ucfirst(str_replace('brand_', '', $intent));
                $products = $query->where('Brand', $brand)->take(4)->get();
                break;

            case strpos($intent, 'budget_') === 0:
                $amount = intval(str_replace('budget_', '', $intent));
                if ($amount < 150000) {
                    $products = $query->where('Price', '<=', 150000)->take(4)->get();
                } elseif ($amount < 300000) {
                    $products = $query->whereBetween('Price', [150000, 300000])->take(4)->get();
                } else {
                    $products = $query->where('Price', '>=', 300000)->take(4)->get();
                }
                break;

            case strpos($intent, 'combined_') === 0:
                $products = $this->getProductsForCombinedIntent($intent, $query);
                break;

            case $intent === 'buy_racket':
                // Hiển thị sản phẩm đa dạng cho tư vấn mua vợt
                $products = $query->where('Categories_ID', 1)
                    ->whereIn('Brand', ['Yonex', 'Lining', 'Victor'])
                    ->inRandomOrder()
                    ->take(4)->get();
                break;

            case $intent === 'budget_consultation':
                // Hiển thị các mức giá khác nhau
                $products = collect();
                $products = $products->concat($query->where('Price', '<=', 150000)->take(2)->get());
                $products = $products->concat($query->whereBetween('Price', [150000, 300000])->take(2)->get());
                $products = $products->concat($query->where('Price', '>=', 300000)->take(1)->get());
                break;

            case $intent === 'most_expensive':
                // Lấy sản phẩm đắt nhất
                $products = $query->orderBy('Price', 'desc')->take(5)->get();
                break;

            case $intent === 'cheapest':
                // Lấy sản phẩm rẻ nhất nhưng vẫn chất lượng
                $products = $query->where('Price', '>', 50000) // Tránh sản phẩm quá rẻ
                    ->orderBy('Price', 'asc')->take(5)->get();
                break;

            case $intent === 'bestseller':
                // Lấy sản phẩm bán chạy hoặc có rating cao
                $products = $query->where(function($q) {
                    $q->where('is_best_seller', true)
                      ->orWhere('is_hot', true) 
                      ->orWhere('is_featured', true);
                })->take(5)->get();
                
                // Nếu không có sản phẩm bestseller, lấy theo Discount_price
                if ($products->isEmpty()) {
                    $products = $query->whereNotNull('Discount_price')
                        ->where('Discount_price', '>', 0)
                        ->orderBy('Price', 'desc')
                        ->take(5)->get();
                }
                break;

            case $intent === 'brand_consultation':
                // Hiển thị đại diện từng thương hiệu
                $products = collect();
                $products = $products->concat($query->where('Brand', 'Yonex')->take(2)->get());
                $products = $products->concat($query->where('Brand', 'Lining')->take(2)->get());
                $products = $products->concat($query->where('Brand', 'Victor')->take(1)->get());
                break;

            default:
                // Sản phẩm nổi bật
                $products = $query->where('Discount_price', '>', 0)
                    ->orderBy('Price', 'desc')
                    ->take(3)->get();
        }

        // Format products for frontend
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