<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatBotController extends Controller
{
    public function chatBadminton(Request $request)
    {
        $question = $request->input("question");
        
        if (!$question) {
            return response()->json([
                "answer" => "Xin chào! Tôi là trợ lý AI thông minh của Vicnex 🤖 Hỏi tôi bất cứ điều gì về cầu lông nhé!",
                "products" => []
            ], 200, [], JSON_UNESCAPED_UNICODE);
        }
        
        $answer = $this->getIntelligentAnswer($question);
        $products = $this->getProducts($question);
        
        return response()->json([
            "answer" => $answer,
            "products" => $products
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
    
    private function getIntelligentAnswer($question)
    {
        $q = strtolower($question);
        
        // ===== 1. CHÀO HỎI & GIỚI THIỆU =====
        if (preg_match('/^(hi|hello|chào|xin chào|hey|yo|hế lô)/i', $question)) {
            return "Chào bạn! 😊 Tôi là trợ lý AI của Vicnex - chuyên gia cầu lông toàn diện! Tôi có thể giúp bạn:\n🏸 Tư vấn thiết bị (vợt, giày, phụ kiện)\n💡 Hướng dẫn kỹ thuật & chiến thuật\n📚 Luật chơi & quy định thi đấu\n🏟️ Đặt sân & dịch vụ\n💪 Luyện tập & dinh dưỡng\nBạn muốn hỏi về điều gì?";
        }
        
        // ===== 2. TUYỂN THỦ CHUYÊN NGHIỆP =====
        if ((strpos($q, "tuyển thủ") !== false || strpos($q, "chuyên nghiệp") !== false || strpos($q, "vđv") !== false || strpos($q, "pro") !== false) && (strpos($q, "tấn công") !== false || strpos($q, "chuyên công") !== false || strpos($q, "công") !== false || strpos($q, "smash") !== false)) {
            return "Tuyển thủ chuyên nghiệp chuyên tấn công! 🏆 Equipment cao cấp cho bạn:\n\n🥇 **Yonex Astrox 100ZZ** - Flagship tấn công (3.2-3.5tr)\n🥈 **Yonex Astrox 99 Pro** - Cân bằng hoàn hảo (2.8-3.1tr)\n🥉 **Lining Aeronaut 9000C** - Power & Speed (2.2-2.4tr)\n\n✅ Specs: Head-heavy, stiff shaft, string tension 26-30lbs\n💡 Pro tip: Kết hợp footwork nhanh với smash power\n\nBạn có sở thích thương hiệu cụ thể không?";
        }
        
        if ((strpos($q, "tuyển thủ") !== false || strpos($q, "chuyên nghiệp") !== false) && (strpos($q, "đơn") !== false || strpos($q, "singles") !== false)) {
            return "Chuyên gia đánh đơn! 🎯 Speed & agility rackets cho bạn:\n\n⚡ **Yonex Nanoflare 800/1000Z** - Tốc độ đỉnh cao\n💪 **Yonex Astrox 88D Pro** - All-round balance\n🎯 **Victor Jetspeed S12** - Precision control\n\n✅ Features: Even balance, fast response, lightweight\n💡 Singles strategy: Change of pace, court coverage\n\nBạn thường chơi phong cách defensive hay aggressive?";
        }
        
        if (strpos($q, "tuyển thủ") !== false || strpos($q, "chuyên nghiệp") !== false || strpos($q, "vđv") !== false || strpos($q, "pro") !== false) {
            return "Chào tuyển thủ! 🏆 Professional equipment consultation:\n\n🏸 **Premium Rackets**: Astrox, Nanoflare, Aeronaut\n👟 **Pro Shoes**: Power Cushion, Aerus Z series  \n🎾 **Tournament Shuttles**: Yonex AS-50, Victor Gold\n⚙️ **Accessories**: Pro grip, vibration dampener\n📈 **Performance**: Analytics, training programs\n\nBạn cần tư vấn category nào đầu tiên?";
        }
        
        // ===== 3. VỢT CẦU LÔNG - TOÀN DIỆN =====
        if (strpos($q, "vợt") !== false) {
            // Người mới bắt đầu
            if (strpos($q, "mới") !== false || strpos($q, "người mới") !== false || strpos($q, "bắt đầu") !== false || strpos($q, "học") !== false || strpos($q, "newbie") !== false) {
                return "🎯 Vợt cho người mới bắt đầu:\n\n👶 **Beginner Level** (50k-150k):\n• Yonex Muscle Power 29L - Siêu nhẹ 85g\n• Yonex Carbonex 21 - Bền bỉ, dễ control\n• Lining Smash XP 610 - Giá tốt, chất lượng ổn\n\n✅ **Đặc điểm cần có**: Nhẹ (<90g), thân mềm, head light\n💡 **Lời khuyên**: Học technique trước, equipment sau\n\nBạn có ngân sách khoảng bao nhiêu?";
            }
            
            // Vợt tấn công
            if (strpos($q, "tấn công") !== false || strpos($q, "smash") !== false || strpos($q, "chuyên công") !== false || strpos($q, "công") !== false || strpos($q, "power") !== false || strpos($q, "mạnh") !== false) {
                return "💥 **POWER RACKETS** - Vợt tấn công mạnh mẽ:\n\n🔥 **Premium Tier**:\n• Yonex Astrox 100ZZ - Ultimate power (3.5tr)\n• Yonex Astrox 99 Pro - Balanced aggression (3tr)\n• Lining Aeronaut 9000C - Speed + Power (2.4tr)\n\n⚡ **Performance Tier**:\n• Victor Jetspeed S12 - Sharp attacks (1.8tr)\n• Yonex Astrox 88S Pro - Solid power (2.1tr)\n\n✅ **Specs**: Head-heavy, stiff shaft, 88-94g\n💪 **String**: 24-28lbs BG80 Power hoặc Aerobite\n\nBạn đã có kinh nghiệm smash chưa?";
            }
            
            // Vợt phòng thủ  
            if (strpos($q, "phòng thủ") !== false || strpos($q, "defend") !== false || strpos($q, "thủ") !== false || strpos($q, "defensive") !== false) {
                return "🛡️ **DEFENSIVE RACKETS** - Vợt phòng thủ chuyên nghiệp:\n\n� **Control Masters**:\n• Yonex Arcsaber 11 Pro - Legendary control\n• Victor Jetspeed S10 - Defense specialist  \n• Yonex Duora Z-Strike - Dual power zones\n\n⚡ **Quick Response**:\n• Yonex Nanoflare 700 - Lightning fast\n• Lining Bladex 900 - Precision control\n\n✅ **Features**: Even balance, flexible shaft, quick recovery\n🥅 **Playstyle**: Counter-attack, net play mastery\n\nBạn thường chơi doubles hay singles?";
            }
            
            // Vợt đánh đơn
            if (strpos($q, "đơn") !== false || strpos($q, "singles") !== false) {
                return "🏃‍♂️ **SINGLES SPECIALIST** - Vợt chuyên đánh đơn:\n\n⚡ **Speed Demons**:\n• Yonex Nanoflare 800/1000Z - Ultimate speed\n• Victor Jetspeed S12 - Court coverage master\n• Yonex Astrox 88D Pro - All-court weapon\n\n🎯 **Precision Tools**:\n• Lining Bladex 900 - Surgical precision\n• Yonex Arcsaber 11 Pro - Touch & feel\n\n✅ **Singles Strategy**: Court coverage, stamina, variety\n💡 **Key Skills**: Drop shots, clears, deceptive shots\n\nBạn thích chơi tốc độ cao hay control game?";
            }
            
            // Vợt đánh đôi
            if (strpos($q, "đôi") !== false || strpos($q, "doubles") !== false || strpos($q, "cặp") !== false) {
                return "👫 **DOUBLES DYNAMICS** - Vợt chuyên đánh đôi:\n\n🚀 **Front Court** (Người trước lưới):\n• Yonex Nanoflare 800 - Lightning reflexes\n• Victor Jetspeed S10 - Quick exchanges\n\n💥 **Back Court** (Người sau sân):\n• Yonex Astrox 99 Pro - Power from back\n• Lining Aeronaut 9000C - Explosive smashes\n\n🎯 **All-Round Doubles**:\n• Yonex Astrox 88S/D Pro - Versatile duo\n\n✅ **Doubles Strategy**: Attack/Defense rotation\nBạn thường chơi ở vị trí nào?";
            }
            
            // Vợt tất cả (general)
            return "🏸 **VỢT CẦU LÔNG** - Tư vấn chuyên sâu:\n\n📊 **Theo trình độ**:\n🥉 Beginner: Muscle Power, Carbonex (50-150k)\n🥈 Intermediate: Arcsaber, Nanoray (200k-1tr)\n🥇 Advanced: Astrox, Nanoflare (1-4tr)\n\n🎯 **Theo phong cách**:\n⚡ Speed: Nanoflare series\n💥 Power: Astrox series\n🛡️ Control: Arcsaber series\n\nBạn muốn tôi tư vấn theo trình độ hay phong cách chơi?";
        }
        
        // ===== 4. GIÀY CẦU LÔNG =====
        if (strpos($q, "giày") !== false || strpos($q, "shoe") !== false || strpos($q, "footwear") !== false) {
            if (strpos($q, "chuyên nghiệp") !== false || strpos($q, "pro") !== false || strpos($q, "thi đấu") !== false) {
                return "👟 **PRO BADMINTON SHOES** - Giày thi đấu chuyên nghiệp:\n\n🏆 **Championship Level**:\n• Yonex Power Cushion Aerus Z2 - Siêu nhẹ 270g (2.8tr)\n• Yonex Power Cushion Infinity - Đệm tối ưu (1.3tr)\n• Lining Ranger TD Pro - Tournament grade (800k)\n\n⚡ **Performance Features**:\n✅ Power Cushion+ technology - Giảm chấn 28%\n✅ Double Russel Mesh - Thoát khí tuyệt vời\n✅ Lateral support - Chống lật cổ chân\n\nBạn hay bị đau chân hay cổ chân không?";
            }
            return "👟 **GIÀY CẦU LÔNG** - Foundation của game:\n\n🎯 **Theo mặt sân**:\n• Sân gỗ: Power Cushion series (grip tốt)\n• Sân nhựa/PU: Ranger series (độ bền cao)  \n• Sân xi măng: Court Ace (chống mài mòn)\n\n💰 **Theo ngân sách**:\n🥉 Entry: 300-500k (Lining Basic, Victor A362)\n🥈 Mid-range: 600k-1tr (PC 55, Ranger TD)\n🥇 Premium: 1-3tr (Aerus Z, Infinity)\n\n❓ Bạn thường chơi sân gì và có vấn đề gì về chân không?";
        }
        
        // ===== 5. CẦU LÔNG (SHUTTLECOCK) =====  
        if (strpos($q, "shuttlecock") !== false || strpos($q, "shuttle") !== false || strpos($q, "quả cầu") !== false || (strpos($q, "cầu") !== false && (strpos($q, "tournament") !== false || strpos($q, "thi đấu") !== false || strpos($q, "luyện tập") !== false || strpos($q, "training") !== false || strpos($q, "as-50") !== false || strpos($q, "victor") !== false))) {
            return "🏸 **SHUTTLECOCK** - Linh hồn của trận đấu:\n\n🏆 **Tournament Grade** (Thi đấu chính thức):\n• Yonex AS-50 - Olympic standard (220k/hộp)\n• Victor Champion No.1 - BWF approved (180k/hộp)\n• Lining A+90 - Premium quality (160k/hộp)\n\n🎯 **Training Grade** (Luyện tập):\n• Yonex AS-30 - Bền, bay ổn định (120k/hộp)\n• Victor Gold No.2 - Tỷ lệ giá/chất tốt (90k/hộp)\n\n💡 **Chọn cầu theo**:\n• Nhiệt độ: Lạnh dùng slow, nóng dùng fast\n• Độ cao: Cao dùng fast, thấp dùng slow  \n• Sân: Indoor slow hơn outdoor\n\nSân của bạn thường nhiệt độ như nào?";
        }
        
        // ===== 6. TRANG PHỤC & PHỤ KIỆN =====
        if (strpos($q, "quần áo") !== false || strpos($q, "trang phục") !== false || strpos($q, "áo") !== false || strpos($q, "quần") !== false || strpos($q, "outfit") !== false) {
            return "👕 **BADMINTON APPAREL** - Style & Performance:\n\n🏆 **Professional Series**:\n• Yonex Tournament Collection - Hàng VĐV (500k-800k)\n• Lining Sudirman Cup - Limited edition (400k-600k)\n• Victor Championship - Premium fabric (350k-500k)\n\n⚡ **Performance Features**:\n✅ Moisture-wicking fabric - Thấm hút mồ hôi\n✅ Anti-bacterial treatment - Kháng khuẩn\n✅ 4-way stretch - Co giãn đa chiều\n✅ UV protection - Chống tia UV\n\n💡 **Sizing tip**: Chọn loose fit để di chuyển tự do\nBạn ưa thích màu sắc hay design nào?";
        }
        
        if (strpos($q, "grip") !== false || strpos($q, "cán vợt") !== false || strpos($q, "quấn cán") !== false) {
            return "🤝 **GRIP TECHNOLOGY** - Kết nối hoàn hảo:\n\n🏆 **Premium Grips**:\n• Yonex Super Grap - Legendary comfort (45k)\n• Tourna Grip - Pro player favorite (35k)  \n• Yonex Tacky Fit - Maximum grip (40k)\n\n💧 **Theo tình trạng tay**:\n• Ra nhiều mồ hôi: Tourna Grip (thấm hút tốt)\n• Tay khô: Super Grap (độ dính vừa phải)\n• Thích mỏng: Tacky Fit Thin (cảm giác sắc nét)\n\n🎯 **Pro Tips**:\n✅ Thay grip mỗi 2-3 tháng\n✅ Over grip mỗi 1-2 tuần nếu chơi thường xuyên\n\nTay bạn có ra nhiều mồ hôi không?";
        }
        
        // Giá cả
        if (strpos($q, "giá") !== false || strpos($q, "bao nhiêu") !== false) {
            return "Bảng giá Vicnex:\n🏸 Vợt: 50k-1M+\n👟 Giày: 200k-800k\n👕 Trang phục: 100k-500k\n🏟️ Sân: 80k-200k/giờ\n\nSản phẩm nào bạn quan tâm?";
        }
        
        // Thương hiệu
        if (strpos($q, "yonex") !== false || strpos($q, "lining") !== false || strpos($q, "victor") !== false) {
            return "Top 3 thương hiệu:\n🇯🇵 Yonex - Vua cầu lông, chất lượng đỉnh cao\n🇨🇳 Lining - Tỷ lệ giá/chất lượng tuyệt vời\n🇹🇼 Victor - Công nghệ hiện đại, bền bỉ\n\nBạn quan tâm thương hiệu nào?";
        }
        
        // ===== 7. KỸ THUẬT CẦU LÔNG =====
        
        // Smash technique
        if (strpos($q, "smash") !== false || strpos($q, "đập") !== false || strpos($q, "cú đập") !== false) {
            return "💥 **SMASH TECHNIQUE** - Vũ khí tối thượng:\n\n🎯 **Perfect Smash Steps**:\n1️⃣ **Preparation**: Sideways stance, racket up high\n2️⃣ **Jump**: Leap with non-racket foot forward  \n3️⃣ **Contact**: Hit at highest point, full extension\n4️⃣ **Follow-through**: Racket down across body\n\n🚀 **Power Secrets**:\n✅ Wrist snap - 70% of power\n✅ Body rotation - Core engagement  \n✅ Leg drive - Jump into the shot\n✅ Timing - Contact at peak height\n\n⚠️ **Common Mistakes**: Late preparation, flat feet, weak wrist\n\n🏋️ **Training drills**: Shadow smash, multi-shuttle, target practice\nBạn muốn luyện power hay accuracy?";
        }
        
        // Clear technique  
        if (strpos($q, "clear") !== false || strpos($q, "đánh cao") !== false || strpos($q, "cầu cao") !== false) {
            return "🌟 **CLEAR TECHNIQUE** - Defensive foundation:\n\n🎯 **Perfect Clear Form**:\n1️⃣ **Setup**: Get behind shuttle early\n2️⃣ **Footwork**: Right foot back (RH player)\n3️⃣ **Backswing**: Racket head drops low\n4️⃣ **Contact**: Hit with upward angle\n5️⃣ **Finish**: High follow-through\n\n📐 **Clear Types**:\n🛡️ **Defensive Clear**: High & deep to baseline\n⚡ **Attack Clear**: Flatter, faster trajectory\n\n💡 **Tactical Usage**:\n✅ Reset rally tempo\n✅ Move opponent to backcourt\n✅ Buy recovery time\n✅ Set up next attack\n\nBạn thường bị thiếu power hay thiếu độ chính xác?";
        }
        
        // Drop shot
        if (strpos($q, "drop") !== false || strpos($q, "cầu cắt") !== false || strpos($q, "cắt cầu") !== false) {
            return "🎯 **DROP SHOT** - Finesse weapon:\n\n✨ **Deceptive Drop**:\n1️⃣ **Deception**: Same preparation as clear/smash\n2️⃣ **Contact**: Gentle touch, racket face open\n3️⃣ **Placement**: Just over net, sharp angle\n4️⃣ **Follow**: Quick recovery to center\n\n🎪 **Drop Shot Variations**:\n🪶 **Slow Drop**: Floats gently over net\n⚡ **Fast Drop**: Quick, steep descent\n🔄 **Cross Drop**: Angled to opposite side\n\n🧠 **Tactical Timing**:\n✅ After driving opponent deep\n✅ When opponent is off-balance\n✅ To break rhythm\n\nBạn muốn học drop thẳng hay drop chéo?";
        }
        
        // Footwork
        if (strpos($q, "di chuyển") !== false || strpos($q, "footwork") !== false || strpos($q, "bước chân") !== false || strpos($q, "chạy") !== false) {
            return "🏃‍♂️ **FOOTWORK MASTERY** - Heart of badminton:\n\n⭐ **6-Point Movement System**:\n1️⃣ **Center Position**: Ready stance, weight forward\n2️⃣ **Front Court**: Lunge step to net\n3️⃣ **Rear Court**: Chasse steps backward\n4️⃣ **Side Court**: Side shuffle/crossover\n5️⃣ **Recovery**: Always return to center\n6️⃣ **Split Step**: Small hop before opponent hits\n\n🎯 **Movement Patterns**:\n📐 **Around-the-Head**: Backhand corner coverage\n🔀 **Cross-Court**: Diagonal movement\n⚡ **Net Rush**: Quick forward movement\n\n💪 **Training Drills**:\n• 6-corner drill\n• Shadow badminton\n• Ladder exercises\n• Multi-shuttle feeding\n\nPhần nào bạn thấy khó nhất?";
        }
        
        // Serve technique
        if (strpos($q, "giao cầu") !== false || strpos($q, "serve") !== false || strpos($q, "phát cầu") !== false) {
            return "🎾 **SERVING MASTERY** - Start strong:\n\n🎯 **Low Serve** (Doubles specialty):\n✅ Contact below waist\n✅ Gentle wrist flick\n✅ Barely clears net\n✅ Target: Front service line\n\n🚀 **High Serve** (Singles weapon):\n✅ Full swing motion  \n✅ Contact high and forward\n✅ Deep to baseline\n✅ Force weak return\n\n🌀 **Flick Serve** (Surprise attack):\n✅ Deceptive low start\n✅ Quick wrist snap\n✅ Fast & flat trajectory\n\n⚖️ **Service Rules**:\n• Underarm motion only\n• Contact below waist\n• Diagonal serve\n• Both feet stationary\n\nBạn chủ yếu chơi đơn hay đôi?";
        }
        
        // General technique
        if (strpos($q, "kỹ thuật") !== false || strpos($q, "technique") !== false || strpos($q, "cách chơi") !== false || strpos($q, "học") !== false) {
            return "🎓 **BADMINTON TECHNIQUE** - Comprehensive guide:\n\n🏸 **Core Techniques**:\n💥 **Overhead**: Smash, clear, drop\n🤚 **Underarm**: Lift, drive, net shot\n🏃 **Footwork**: 6-point movement\n🎾 **Serve**: Low, high, flick variations\n\n📊 **Learning Priority**:\n1️⃣ **Basics**: Grip, stance, footwork\n2️⃣ **Strokes**: Clear, drop, smash progression  \n3️⃣ **Serves**: Master low serve first\n4️⃣ **Advanced**: Deception, variation, tactics\n\n🎯 **Practice Structure**:\n• 20% footwork drills\n• 40% stroke technique  \n• 20% serve practice\n• 20% game situations\n\nBạn muốn focus vào technique nào trước?";
        }
        
        // ===== 8. CHIẾN THUẬT & TACTICS =====
        if (strpos($q, "chiến thuật") !== false || strpos($q, "tactics") !== false || strpos($q, "strategy") !== false || strpos($q, "tactic") !== false) {
            if (strpos($q, "đơn") !== false || strpos($q, "singles") !== false) {
                return "🎯 **SINGLES STRATEGY** - 1v1 mastery:\n\n🏃‍♂️ **Movement Game**:\n✅ Force opponent to all 4 corners\n✅ Change pace: slow-fast-slow rhythm\n✅ Use length: deep clears to short drops\n✅ Stamina management: conserve energy\n\n⚡ **Attack Patterns**:\n📐 **Cross-court clear → Straight drop**\n🔄 **Deep serve → Net attack**  \n💥 **Lift → Smash → Drop follow-up**\n\n🛡️ **Defensive Strategy**:\n• Make opponent move more\n• Counter-attack from defense\n• Patience over power\n• Error-free badminton\n\nPhong cách nào phù hợp với bạn: All-court hay baseline?";
            }
            
            return "🧠 **BADMINTON TACTICS** - Mental game:\n\n🎯 **Universal Principles**:\n1️⃣ **Control center**: Dominate middle court\n2️⃣ **Create openings**: Make opponent move\n3️⃣ **Exploit weaknesses**: Target backhand/movement\n4️⃣ **Vary shots**: Keep opponent guessing\n5️⃣ **Pressure points**: Attack when ahead\n\n📊 **Game Phases**:\n🔄 **Early Game**: Establish patterns\n⚡ **Mid Game**: Build pressure  \n🏆 **End Game**: Close out points\n\nBạn muốn học tactics cho đơn hay đôi?";
        }
        
        // ===== 9. LUẬT CHƠI & QUY ĐỊNH =====
        if (strpos($q, "luật") !== false || strpos($q, "rule") !== false || strpos($q, "quy định") !== false || strpos($q, "regulation") !== false) {
            if (strpos($q, "điểm") !== false || strpos($q, "scoring") !== false || strpos($q, "point") !== false) {
                return "📊 **SCORING SYSTEM** - Rally Point System:\n\n🏆 **Game Structure**:\n• Best of 3 games\n• First to 21 points wins game\n• Must win by 2 points\n• Maximum 30 points (game ends at 30)\n\n⚡ **Point Rules**:\n✅ Every rally = 1 point\n✅ Winner of rally serves next\n✅ Server calls score first\n\n⏱️ **Intervals**:\n• 60 seconds at 11 points\n• 120 seconds between games\n\n� **Deuce Situations**:\n• 20-20: Play to 22\n• 29-29: Next point wins\n\nCó tình huống scoring nào bạn thắc mắc?";
            }
            
            return "⚖️ **BADMINTON RULES** - Official BWF regulations:\n\n🚫 **Common Faults**:\n• Double hit (đánh 2 lần)\n• Carry/sling (mang cầu)\n• Net contact (chạm lưới)\n• Invasion (vượt sân)\n• Service faults (lỗi giao cầu)\n\n🏸 **Service Rules**:\n• Underarm motion only\n• Below waist contact\n• Diagonal service\n• Alternate courts after points\n\n📏 **Court Dimensions**:\n• Singles: 13.4m x 5.18m\n• Doubles: 13.4m x 6.1m\n• Net height: 1.55m at posts, 1.524m at center\n\nBạn muốn hiểu rõ luật nào cụ thể?";
        }
        
        // ===== 10. TRAINING & FITNESS =====
        if (strpos($q, "luyện tập") !== false || strpos($q, "training") !== false || strpos($q, "tập") !== false || strpos($q, "fitness") !== false || strpos($q, "thể lực") !== false) {
            return "💪 **BADMINTON TRAINING** - Complete program:\n\n🏃‍♂️ **Physical Fitness** (40% of training):\n• Agility: Ladder drills, cone work\n• Explosive power: Jump squats, plyometrics  \n• Endurance: Interval running, court sprints\n• Flexibility: Dynamic stretching, yoga\n\n🏸 **Technical Skills** (40% of training):\n• Multi-shuttle feeding\n• Shadow badminton\n• Wall practice\n• Video analysis\n\n🧠 **Mental & Tactical** (20% of training):\n• Match simulation\n• Pressure situations\n• Pattern recognition\n• Mental toughness drills\n\n📅 **Weekly Schedule**:\n• 3x technical sessions\n• 2x fitness workouts  \n• 1x match play\n• 1x rest/recovery\n\nBạn muốn program cho level nào?";
        }
        
        // ===== 11. DINH DƯỠNG & SỨC KHỎE =====
        if (strpos($q, "dinh dưỡng") !== false || strpos($q, "nutrition") !== false || strpos($q, "ăn uống") !== false || strpos($q, "diet") !== false || strpos($q, "sức khỏe") !== false) {
            return "🥗 **SPORTS NUTRITION** - Fuel your performance:\n\n⚡ **Pre-Game** (2-3 hours before):\n• Complex carbs: Brown rice, oatmeal\n• Lean protein: Chicken, fish, eggs\n• Hydration: 500ml water\n• Avoid: Heavy, fatty, spicy foods\n\n🏸 **During Game**:\n• Isotonic drinks: Pocari, Aquarius\n• Quick carbs: Banana, energy gel\n• Small sips regularly\n\n🔄 **Post-Game** (30 min window):\n• Protein shake + fruit\n• Chocolate milk (3:1 carb:protein ratio)\n• Rehydration: 1.5x fluid lost\n\n💊 **Supplements**:\n✅ Recommended: Whey protein, multivitamin\n❓ Optional: Creatine, BCAAs\n❌ Avoid: Banned substances\n\nBạn có mục tiêu cụ thể gì về nutrition?";
        }
        
        // ===== 12. CHẤN THƯƠNG & PHÒNG NGỪA =====
        if (strpos($q, "chấn thương") !== false || strpos($q, "injury") !== false || strpos($q, "đau") !== false || strpos($q, "bị thương") !== false || strpos($q, "prevention") !== false) {
            return "🏥 **INJURY PREVENTION** - Stay in the game:\n\n⚠️ **Common Injuries**:\n🦵 **Ankle sprain**: 40% of badminton injuries\n🦴 **Knee problems**: Jumper's knee, ACL\n💪 **Shoulder**: Rotator cuff, impingement\n🤲 **Wrist**: Overuse, tendonitis\n\n🛡️ **Prevention Strategies**:\n✅ **Warm-up**: 10-15 min dynamic stretching\n✅ **Cool-down**: Static stretching, foam rolling\n✅ **Strength**: Core, glutes, rotator cuff\n✅ **Proper footwear**: Court-specific shoes\n✅ **Load management**: Rest days, periodization\n\n🩹 **First Aid RICE**:\n• **Rest**: Stop activity immediately\n• **Ice**: 15-20 min every 2-3 hours\n• **Compression**: Elastic bandage\n• **Elevation**: Raise above heart level\n\nBạn có vấn đề gì cụ thể về chấn thương?";
        }
        
        // ===== 13. GIẢI ĐẤU & THI ĐẤU =====  
        if (strpos($q, "giải đấu") !== false || strpos($q, "tournament") !== false || strpos($q, "thi đấu") !== false || strpos($q, "competition") !== false || strpos($q, "olympic") !== false || strpos($q, "world") !== false) {
            return "🏆 **MAJOR TOURNAMENTS** - Badminton calendar:\n\n🥇 **BWF Major Events**:\n• **Olympic Games**: 4-year cycle, ultimate goal\n• **World Championships**: Annual, all 5 categories\n• **All England**: Oldest & most prestigious\n• **Thomas/Uber Cup**: Men's/Women's team events\n• **Sudirman Cup**: Mixed team championship\n\n⭐ **BWF World Tour**:\n� **Super 1000**: Indonesia, China, All England\n🥈 **Super 750**: Malaysia, Denmark, Japan  \n🥉 **Super 500**: India, Thailand, Singapore\n\n🇻🇳 **Vietnam Circuit**:\n• Yonex Vietnam Open\n• National Championships  \n• Regional tournaments\n• Club competitions\n\n📅 **Competition Prep**:\n✅ Entry requirements & deadlines\n✅ Equipment checks & backups\n✅ Mental preparation routines\n\nBạn có ý định tham gia giải nào không?";
        }
        
        // ===== 14. LỊCH SỬ CẦU LÔNG =====
        if (strpos($q, "lịch sử") !== false || strpos($q, "history") !== false || strpos($q, "origin") !== false || strpos($q, "xuất xứ") !== false || strpos($q, "bắt nguồn") !== false) {
            return "📚 **BADMINTON HISTORY** - Rich heritage:\n\n🏰 **Origins** (1860s):\n• Started in **Badminton House**, England\n• Evolved from ancient game **Battledore**\n• British officers in India played **Poona**\n• First rules written in 1877\n\n🌍 **Global Development**:\n� **1899**: First All England Championships\n📅 **1934**: BWF (Badminton World Federation) formed\n📅 **1992**: Olympic debut in Barcelona\n📅 **1996**: Mixed doubles added\n\n🏆 **Legendary Players**:\n🇨🇳 **Lin Dan**: 2x Olympic champion, Super Dan\n🇮🇩 **Rudy Hartono**: 8x All England winner\n🇩🇰 **Peter Gade**: European legend\n🇲🇾 **Lee Chong Wei**: 3x Olympic silver\n\n🇻🇳 **Vietnam Badminton**:\n• Nguyễn Tiến Minh: First world top 10\n• Growing popularity since 2000s\n\nBạn muốn biết về ai cụ thể?";
        }
        
        // ===== 15. ĐẶT SÂN & DỊCH VỤ =====
        if (strpos($q, "sân") !== false || strpos($q, "đặt") !== false || strpos($q, "booking") !== false || strpos($q, "court") !== false) {
            return "🏟️ **COURT BOOKING** - Vicnex facilities:\n\n🏸 **Court Specifications**:\n✅ **Surface**: Premium wooden flooring\n✅ **Lighting**: LED professional lighting\n✅ **Ventilation**: Climate control system  \n✅ **Net**: BWF regulation height\n✅ **Lines**: Tournament standard marking\n\n💰 **Pricing Structure**:\n🌅 **Morning** (6AM-12PM): 80-100k/hour\n☀️ **Afternoon** (12PM-6PM): 100-120k/hour\n� **Evening** (6PM-10PM): 150-200k/hour\n🌃 **Night** (10PM-12AM): 120-150k/hour\n\n🎁 **Special Offers**:\n• 24h advance booking: 10% discount\n• Monthly membership: 15% off\n• Student rate: 20% off (with ID)\n• Group booking (3+ courts): 5% off\n\n📱 **Booking Methods**:\n• Online platform\n• Mobile app\n• Phone reservation\n• Walk-in (subject to availability)\n\nBạn muốn đặt sân khi nào?";
        }
        
        // ===== 16. THƯƠNG HIỆU & SO SÁNH =====
        if (strpos($q, "thương hiệu") !== false || strpos($q, "brand") !== false || strpos($q, "yonex") !== false || strpos($q, "lining") !== false || strpos($q, "victor") !== false || strpos($q, "so sánh") !== false || strpos($q, "compare") !== false) {
            return "🏷️ **BRAND COMPARISON** - Choose your weapon:\n\n🇯🇵 **YONEX** - The King:\n✅ **Strengths**: Premium quality, innovation, pro endorsements\n✅ **Famous for**: Astrox, Nanoflare, Power Cushion\n✅ **Price**: Premium tier (1-4tr)\n✅ **Best for**: Serious players, tournament play\n\n🇨🇳 **LINING** - Value Champion:\n✅ **Strengths**: Great price/performance ratio\n✅ **Famous for**: Aeronaut, Bladex, Ranger series  \n✅ **Price**: Mid-range (500k-2.5tr)\n✅ **Best for**: Recreational to advanced players\n\n🇹🇼 **VICTOR** - Tech Innovation:\n✅ **Strengths**: Modern technology, durability\n✅ **Famous for**: Jetspeed, TK-F series\n✅ **Price**: Competitive pricing (600k-3tr)\n✅ **Best for**: Power players, tech enthusiasts\n\n🎯 **Quick Recommendation**:\n• **Budget**: Lining Basic series\n• **Performance**: Yonex intermediate\n• **Professional**: Yonex flagship models\n\nBạn ưu tiên giá cả hay chất lượng?";
        }
        
        // ===== 17. SEASONAL & WEATHER =====
        if (strpos($q, "thời tiết") !== false || strpos($q, "weather") !== false || strpos($q, "mùa") !== false || strpos($q, "season") !== false || strpos($q, "nhiệt độ") !== false) {
            return "🌤️ **WEATHER IMPACT** - Adapt your game:\n\n🌡️ **Temperature Effects**:\n❄️ **Cold Weather** (<20°C):\n• Use SLOW speed shuttles\n• Longer warm-up needed\n• Muscles need more prep time\n\n🔥 **Hot Weather** (>28°C):  \n• Use FAST speed shuttles\n• Increase hydration\n• Shorter, intense rallies\n\n💨 **Humidity Impact** (>70%):\n• Shuttles fly slower & drop faster\n• Grip becomes slippery\n• Energy depletes quicker\n\n🏠 **Indoor vs Outdoor**:\n✅ **Indoor**: Consistent conditions, no wind\n❌ **Outdoor**: Variable conditions, sun glare\n\n🎯 **Adaptation Strategies**:\n• Adjust shuttle speed selection\n• Modify training intensity  \n• Change grip frequency\n• Hydration planning\n\nBạn thường chơi indoor hay outdoor?";
        }
        
        // ===== 18. KIDS & JUNIOR DEVELOPMENT =====
        if (strpos($q, "trẻ em") !== false || strpos($q, "kids") !== false || strpos($q, "junior") !== false || strpos($q, "học sinh") !== false || strpos($q, "children") !== false) {
            return "👶 **JUNIOR BADMINTON** - Building future champions:\n\n🎯 **Age Group Programs**:\n🐣 **Mini Badminton** (5-8 years):\n• Shorter rackets (21-23 inches)\n• Lower nets (1.2m height)\n• Foam shuttles\n• Fun games & activities\n\n🌱 **Junior Development** (9-12 years):\n• Youth rackets (24-25 inches)\n• Proper technique foundation\n• Basic footwork patterns\n• Mini tournaments\n\n🚀 **Competitive Youth** (13-17 years):\n• Adult equipment transition\n• Advanced technique training\n• Tournament participation\n• College preparation\n\n🏆 **Key Focus Areas**:\n✅ **Fun first**: Keep it enjoyable\n✅ **Fundamentals**: Proper technique base\n✅ **Fitness**: Age-appropriate conditioning\n✅ **Mental**: Confidence building\n\n📚 **Benefits for Kids**:\n• Hand-eye coordination\n• Social skills development\n• Discipline & focus\n• Physical fitness\n\nBạn muốn tìm program cho độ tuổi nào?";
        }
        
        // ===== 19. WOMEN'S BADMINTON =====
        if (strpos($q, "nữ") !== false || strpos($q, "women") !== false || strpos($q, "female") !== false || strpos($q, "phụ nữ") !== false || strpos($q, "chị em") !== false) {
            return "👩 **WOMEN'S BADMINTON** - Empowering female athletes:\n\n🏆 **Legendary Female Players**:\n🇨🇳 **Zhang Ning**: 2x Olympic champion\n🇮🇳 **Saina Nehwal**: First Indian Olympic medalist\n🇪🇸 **Carolina Marín**: European sensation\n🇹🇼 **Tai Tzu-ying**: Current world #1\n\n💪 **Women-Specific Considerations**:\n🏸 **Equipment**: Lighter rackets (80-88g)\n👟 **Shoes**: Women's specific fit & design\n👕 **Apparel**: Comfortable, supportive sportswear\n\n🎯 **Training Focus**:\n✅ **Technique over power**: Precision & placement\n✅ **Speed & agility**: Quick reactions\n✅ **Mental toughness**: Competitive mindset\n✅ **Injury prevention**: Especially knee & ankle\n\n🌟 **Women's Programs**:\n• Ladies-only sessions\n• Beginner-friendly classes\n• Social tournaments\n• Fitness-focused training\n\n💡 **Why Women Excel**:\n• Superior technique\n• Better court awareness\n• Strategic thinking\n• Consistent performance\n\nBạn muốn tham gia group nữ không?";
        }
        
        // ===== 20. GIẢI TRÍ & SOCIAL =====
        if (strpos($q, "giải trí") !== false || strpos($q, "recreational") !== false || strpos($q, "fun") !== false || strpos($q, "social") !== false || strpos($q, "bạn bè") !== false || strpos($q, "team building") !== false) {
            return "🎉 **RECREATIONAL BADMINTON** - Fun & friendship:\n\n🏸 **Social Play Benefits**:\n✅ **Fitness**: Great cardio workout\n✅ **Social**: Meet like-minded people\n✅ **Stress relief**: Mental relaxation\n✅ **Flexibility**: Play at your own pace\n\n🎯 **Casual Game Formats**:\n👫 **Mixed Doubles**: Men + women teams\n🔄 **Round Robin**: Everyone plays everyone\n🎲 **King of Court**: Winner stays format\n🏆 **Mini Tournaments**: Short competitions\n\n🎊 **Social Events**:\n• Weekly club nights\n• BBQ after tournaments\n• Holiday celebrations\n• Team outings\n\n💰 **Budget-Friendly Options**:\n🏸 **Equipment**: Entry-level rackets (100-300k)\n👕 **Clothing**: Comfortable sportswear\n🏟️ **Court fees**: Off-peak hours\n🍕 **Post-game**: Affordable dining\n\n🤝 **Making Friends**:\n• Join beginner groups\n• Attend social events\n• Volunteer at tournaments\n• Be encouraging to others\n\nBạn muốn tham gia group nào?";
        }
        
        // Sản phẩm đắt nhất
        if (strpos($q, "dat nhat") !== false || strpos($q, "expensive") !== false || strpos($q, "cao cap") !== false) {
            return "💎 TOP sản phẩm cao cấp:\n🏸 Yonex Astrox 100ZZ - 3.5-3.9tr\n🏸 Yonex Duora Z-Strike - 3.2-3.6tr\n👟 Yonex Aerus Z2 - 2.5-2.8tr\n\n✅ Ưu điểm: Công nghệ Namd, carbon cao cấp, VĐV Olympic dùng\n\nBạn quan tâm loại nào?";
        }
        
        // Sản phẩm rẻ nhất
        if (strpos($q, "re nhat") !== false || strpos($q, "gia re") !== false || strpos($q, "binh dan") !== false) {
            return "💰 Sản phẩm giá tốt:\n🏸 Yonex Muscle Power 29L - 80-120k\n🏸 Yonex Carbonex 21 - 90-130k\n👟 Lining Ranger TD - 300-400k\n\n✅ Chất lượng: Hàng chính hãng, bảo hành đầy đủ\n\nCần tư vấn chi tiết không?";
        }
        
        // Sản phẩm bán chạy
        if (strpos($q, "ban chay") !== false || strpos($q, "bestseller") !== false || strpos($q, "hot") !== false) {
            return "🔥 Top bán chạy:\n🏸 Yonex Astrox 99 Pro - Cân bằng tốt\n🏸 Yonex Nanoflare 800 - Tốc độ cao\n👟 Yonex Power Cushion 55 - Êm ái\n\n✅ Tại sao hot: Đa năng, giá hợp lý, review tốt\n\nMuốn xem chi tiết?";
        }
        
        // ===== 21. ĐA DẠNG PATTERNS KHÁC =====
        
        // Mental game & psychology
        if (strpos($q, "mental") !== false || strpos($q, "tâm lý") !== false || strpos($q, "psychology") !== false || strpos($q, "confidence") !== false || strpos($q, "pressure") !== false) {
            return "🧠 **MENTAL GAME** - Win with your mind:\n\n💪 **Mental Strengths**:\n✅ **Confidence**: Believe in your abilities\n✅ **Focus**: Stay in the present moment\n✅ **Resilience**: Bounce back from setbacks\n✅ **Composure**: Stay calm under pressure\n\n🎯 **Pressure Management**:\n• Pre-game routines\n• Breathing techniques  \n• Positive self-talk\n• Visualization practice\n\n🏆 **Competition Mindset**:\n• Process over outcome\n• Point-by-point focus\n• Learn from losses\n• Celebrate improvements\n\nBạn gặp khó khăn gì về mental game?";
        }
        
        // Advanced techniques  
        if (strpos($q, "advanced") !== false || strpos($q, "nâng cao") !== false || strpos($q, "pro level") !== false || strpos($q, "deception") !== false) {
            return "🎭 **ADVANCED TECHNIQUES** - Next level skills:\n\n🪄 **Deception Masters**:\n• **Slice drop**: Racket face manipulation\n• **Cross drop**: Last-second wrist change\n• **Fake smash**: Preparation without power\n• **Around-the-head**: Backhand disguise\n\n⚡ **Power Techniques**:\n• **Jump smash**: Maximum power generation\n• **Steep smash**: Sharp downward angle\n• **Half-smash**: Controlled power shots\n\n🎯 **Precision Shots**:\n• **Tight net**: Tumbling over net\n• **Brick wall defense**: Impenetrable lifts\n• **Counter-attack drives**: Turn defense to offense\n\nBạn muốn master technique nào?";
        }
        
        // Equipment maintenance
        if (strpos($q, "bảo dưỡng") !== false || strpos($q, "maintenance") !== false || strpos($q, "care") !== false || strpos($q, "chăm sóc") !== false) {
            return "🔧 **EQUIPMENT CARE** - Extend lifespan:\n\n🏸 **Racket Maintenance**:\n✅ **String tension**: Check monthly, restring 4-6 months\n✅ **Frame care**: Clean after play, check for cracks\n✅ **Grip replacement**: Every 2-3 months or when worn\n✅ **Storage**: Room temperature, avoid extreme conditions\n\n👟 **Shoe Care**:\n• Clean after each session\n• Air dry, never direct heat\n• Rotate pairs to extend life\n• Replace when outsole worn\n\n🎾 **Shuttle Storage**:\n• Keep in original tube\n• Room temperature & humidity\n• Use within 6 months\n\nBạn cần tư vấn maintenance gì cụ thể?";
        }
        
        // FALLBACK - Comprehensive response
        return "🤖 **VICNEX AI ASSISTANT** - Tôi có thể giúp bạn với:\n\n🏸 **EQUIPMENT**: Rackets, shoes, shuttles, apparel\n💡 **TECHNIQUE**: Smash, clear, drop, serve, footwork  \n🧠 **STRATEGY**: Singles, doubles, tactics, mental game\n⚖️ **RULES**: Scoring, faults, regulations, tournaments\n🏟️ **FACILITIES**: Court booking, pricing, programs\n💪 **TRAINING**: Fitness, drills, nutrition, injury prevention\n📚 **KNOWLEDGE**: History, brands, comparisons, tips\n👥 **PROGRAMS**: Juniors, women's, recreational, competitive\n\n💬 **Hỏi tôi bất cứ điều gì về cầu lông!** \n\nVí dụ: \"Vợt nào tốt cho người mới?\", \"Cách smash mạnh?\", \"Luật đánh đôi?\", \"Giá đặt sân?\"\n\nBạn muốn tìm hiểu về vấn đề nào?";
    }
    
    private function getProducts($question)
    {
        $q = strtolower($question);
        $products = [];
        
        // === PROFESSIONAL PLAYERS ===
        if ((strpos($q, "tuyển thủ") !== false || strpos($q, "chuyên nghiệp") !== false || strpos($q, "vđv") !== false || strpos($q, "pro") !== false) && (strpos($q, "tấn công") !== false || strpos($q, "chuyên công") !== false || strpos($q, "công") !== false)) {
            $products = [
                [
                    "id" => 3,
                    "name" => "Vợt Yonex Astrox 100ZZ",
                    "price" => 3200000,
                    "original_price" => 3500000,
                    "image" => "http://localhost:8000/uploads/products/yonex-astrox100zz.jpg",
                    "brand" => "Yonex",
                    "description" => "Ultimate attack racket với Namd technology..."
                ],
                [
                    "id" => 8,
                    "name" => "Vợt Yonex Astrox 99 Pro",
                    "price" => 2800000,
                    "original_price" => 3100000,
                    "image" => "http://localhost:8000/uploads/products/yonex-astrox99pro.jpg",
                    "brand" => "Yonex",
                    "description" => "Perfect balance attack-defense chuyên nghiệp..."
                ],
                [
                    "id" => 4,
                    "name" => "Vợt Lining Aeronaut 9000C",
                    "price" => 2200000,
                    "original_price" => 2400000,
                    "image" => "http://localhost:8000/uploads/products/lining-an9000c.jpg",
                    "brand" => "Lining",
                    "description" => "Explosive power & speed combination..."
                ]
            ];
        }
        
        // === SINGLES SPECIALISTS ===  
        else if ((strpos($q, "tuyển thủ") !== false || strpos($q, "chuyên nghiệp") !== false) && (strpos($q, "đơn") !== false || strpos($q, "singles") !== false)) {
            $products = [
                [
                    "id" => 9,
                    "name" => "Vợt Yonex Nanoflare 800",
                    "price" => 2500000,
                    "original_price" => 2700000,
                    "image" => "http://localhost:8000/uploads/products/yonex-nanoflare800.jpg",
                    "brand" => "Yonex",
                    "description" => "Lightning speed cho singles domination..."
                ],
                [
                    "id" => 10,
                    "name" => "Vợt Yonex Astrox 88D Pro",
                    "price" => 2100000,
                    "original_price" => 2400000,
                    "image" => "http://localhost:8000/uploads/products/yonex-astrox88d.jpg",
                    "brand" => "Yonex",
                    "description" => "All-court weapon cho singles flexibility..."
                ]
            ];
        }
        
        // === PROFESSIONAL GENERAL ===
        else if (strpos($q, "tuyển thủ") !== false || strpos($q, "chuyên nghiệp") !== false || strpos($q, "vđv") !== false || strpos($q, "pro") !== false) {
            $products = [
                [
                    "id" => 3,
                    "name" => "Vợt Yonex Astrox 100ZZ",
                    "price" => 3200000,
                    "original_price" => 3500000,
                    "image" => "http://localhost:8000/uploads/products/yonex-astrox100zz.jpg",
                    "brand" => "Yonex",
                    "description" => "Flagship premium cho professionals..."
                ],
                [
                    "id" => 11,
                    "name" => "Giày Yonex Power Cushion Aerus Z2",
                    "price" => 2550000,
                    "original_price" => 2800000,
                    "image" => "http://localhost:8000/uploads/products/yonex-aerusz2.jpg",
                    "brand" => "Yonex",
                    "description" => "Ultra-light professional court shoes..."
                ]
            ];
        }
        
        // === TECHNIQUE QUERIES ===
        else if (strpos($q, "smash") !== false || strpos($q, "đập") !== false || strpos($q, "cú đập") !== false) {
            $products = [
                [
                    "id" => 3,
                    "name" => "Vợt Yonex Astrox 100ZZ",
                    "price" => 3200000,
                    "original_price" => 3500000,
                    "image" => "http://localhost:8000/uploads/products/yonex-astrox100zz.jpg",
                    "brand" => "Yonex",
                    "description" => "Maximum smash power racket..."
                ],
                [
                    "id" => 12,
                    "name" => "Dây cước BG80 Power",
                    "price" => 180000,
                    "original_price" => 200000,
                    "image" => "http://localhost:8000/uploads/products/bg80-power.jpg",
                    "brand" => "Yonex",
                    "description" => "String cho smash power tối đa..."
                ]
            ];
        }
        
        // === FOOTWEAR QUERIES ===
        else if (strpos($q, "giày") !== false || strpos($q, "shoe") !== false) {
            if (strpos($q, "chuyên nghiệp") !== false || strpos($q, "pro") !== false) {
                $products = [
                    [
                        "id" => 11,
                        "name" => "Giày Yonex Power Cushion Aerus Z2",
                        "price" => 2550000,
                        "original_price" => 2800000,
                        "image" => "http://localhost:8000/uploads/products/yonex-aerusz2.jpg",
                        "brand" => "Yonex",
                        "description" => "Professional tournament shoes..."
                    ],
                    [
                        "id" => 13,
                        "name" => "Giày Yonex Power Cushion Infinity",
                        "price" => 1200000,
                        "original_price" => 1350000,
                        "image" => "http://localhost:8000/uploads/products/yonex-infinity.jpg",
                        "brand" => "Yonex",
                        "description" => "Ultimate comfort & support..."
                    ]
                ];
            } else {
                $products = [
                    [
                        "id" => 14,
                        "name" => "Giày Lining Ranger TD",
                        "price" => 450000,
                        "original_price" => 500000,
                        "image" => "http://localhost:8000/uploads/products/lining-ranger-td.jpg",
                        "brand" => "Lining",
                        "description" => "Affordable performance shoes..."
                    ],
                    [
                        "id" => 15,
                        "name" => "Giày Yonex Power Cushion 55",
                        "price" => 650000,
                        "original_price" => 750000,
                        "image" => "http://localhost:8000/uploads/products/yonex-pc55.jpg",
                        "brand" => "Yonex",
                        "description" => "Mid-range comfort & durability..."
                    ]
                ];
            }
        }
        
        // === SHUTTLECOCK QUERIES ===
        else if ((strpos($q, "cầu lông") !== false && strpos($q, "vợt") === false) || strpos($q, "shuttlecock") !== false || strpos($q, "shuttle") !== false) {
            $products = [
                [
                    "id" => 16,
                    "name" => "Cầu Yonex AS-50 Tournament",
                    "price" => 220000,
                    "original_price" => 250000,
                    "image" => "http://localhost:8000/uploads/products/yonex-as50.jpg",
                    "brand" => "Yonex",
                    "description" => "Olympic standard shuttlecock..."
                ],
                [
                    "id" => 17,
                    "name" => "Cầu Victor Champion No.1",
                    "price" => 180000,
                    "original_price" => 200000,
                    "image" => "http://localhost:8000/uploads/products/victor-champion.jpg",
                    "brand" => "Victor",
                    "description" => "BWF approved tournament grade..."
                ]
            ];
        }
        
        // === APPAREL QUERIES ===
        else if (strpos($q, "quần áo") !== false || strpos($q, "trang phục") !== false || strpos($q, "áo") !== false || strpos($q, "quần") !== false) {
            $products = [
                [
                    "id" => 18,
                    "name" => "Áo Yonex Tournament Collection",
                    "price" => 650000,
                    "original_price" => 750000,
                    "image" => "http://localhost:8000/uploads/products/yonex-tournament-shirt.jpg",
                    "brand" => "Yonex",
                    "description" => "Professional tournament apparel..."
                ],
                [
                    "id" => 19,
                    "name" => "Quần Lining Sudirman Cup",
                    "price" => 420000,
                    "original_price" => 500000,
                    "image" => "http://localhost:8000/uploads/products/lining-sudirman-shorts.jpg",
                    "brand" => "Lining",
                    "description" => "Limited edition tournament shorts..."
                ]
            ];
        }
        
        // === GRIP & ACCESSORIES ===
        else if (strpos($q, "grip") !== false || strpos($q, "cán vợt") !== false || strpos($q, "quấn cán") !== false) {
            $products = [
                [
                    "id" => 20,
                    "name" => "Grip Yonex Super Grap",
                    "price" => 45000,
                    "original_price" => 50000,
                    "image" => "http://localhost:8000/uploads/products/yonex-super-grap.jpg",
                    "brand" => "Yonex",
                    "description" => "Legendary comfort grip..."
                ],
                [
                    "id" => 21,
                    "name" => "Over Grip Tourna Grip",
                    "price" => 35000,
                    "original_price" => 40000,
                    "image" => "http://localhost:8000/uploads/products/tourna-grip.jpg",
                    "brand" => "Tourna",
                    "description" => "Pro player favorite grip..."
                ]
            ];
        }
        
        // === RACKET CATEGORIES ===
        
        // Beginner rackets
        else if (strpos($q, "vợt") !== false && (strpos($q, "mới") !== false || strpos($q, "người mới") !== false || strpos($q, "bắt đầu") !== false || strpos($q, "học") !== false || strpos($q, "newbie") !== false)) {
            $products = [
                [
                    "id" => 1,
                    "name" => "Vợt Yonex Muscle Power 29 Light",
                    "price" => 85000,
                    "original_price" => 95000,
                    "image" => "http://localhost:8000/uploads/products/yonex-mp29l.jpg",
                    "brand" => "Yonex",
                    "description" => "Ultra-light beginner friendly racket..."
                ],
                [
                    "id" => 2,
                    "name" => "Vợt Yonex Carbonex 21",
                    "price" => 120000,
                    "original_price" => 135000,
                    "image" => "http://localhost:8000/uploads/products/yonex-carbonex21.jpg",
                    "brand" => "Yonex", 
                    "description" => "Classic durable racket cho technique building..."
                ],
                [
                    "id" => 22,
                    "name" => "Vợt Lining Smash XP 610",
                    "price" => 95000,
                    "original_price" => 110000,
                    "image" => "http://localhost:8000/uploads/products/lining-smash-xp610.jpg",
                    "brand" => "Lining", 
                    "description" => "Budget-friendly quality racket..."
                ]
            ];
        }
        
        // Attack rackets
        else if (strpos($q, "vợt") !== false && (strpos($q, "tấn công") !== false || strpos($q, "chuyên công") !== false || strpos($q, "power") !== false || strpos($q, "mạnh") !== false)) {
            $products = [
                [
                    "id" => 3,
                    "name" => "Vợt Yonex Astrox 100ZZ",
                    "price" => 3200000,
                    "original_price" => 3500000,
                    "image" => "http://localhost:8000/uploads/products/yonex-astrox100zz.jpg",
                    "brand" => "Yonex",
                    "description" => "Ultimate power generation racket..."
                ],
                [
                    "id" => 4,
                    "name" => "Vợt Lining Aeronaut 9000C",
                    "price" => 2200000,
                    "original_price" => 2400000,
                    "image" => "http://localhost:8000/uploads/products/lining-an9000c.jpg",
                    "brand" => "Lining",
                    "description" => "High-performance attack specialist..."
                ],
                [
                    "id" => 8,
                    "name" => "Vợt Yonex Astrox 99 Pro",
                    "price" => 2800000,
                    "original_price" => 3100000,
                    "image" => "http://localhost:8000/uploads/products/yonex-astrox99pro.jpg",
                    "brand" => "Yonex", 
                    "description" => "Balanced aggressive performance..."
                ]
            ];
        }
        
        // Defensive rackets  
        else if (strpos($q, "vợt") !== false && (strpos($q, "phòng thủ") !== false || strpos($q, "defend") !== false || strpos($q, "thủ") !== false || strpos($q, "control") !== false || strpos($q, "defensive") !== false)) {
            $products = [
                [
                    "id" => 23,
                    "name" => "Vợt Yonex Arcsaber 11 Pro",
                    "price" => 2400000,
                    "original_price" => 2700000,
                    "image" => "http://localhost:8000/uploads/products/yonex-arcsaber11pro.jpg",
                    "brand" => "Yonex",
                    "description" => "Legendary control & feel racket..."
                ],
                [
                    "id" => 24,
                    "name" => "Vợt Victor Jetspeed S10",
                    "price" => 1800000,
                    "original_price" => 2000000,
                    "image" => "http://localhost:8000/uploads/products/victor-jetspeed-s10.jpg",
                    "brand" => "Victor",
                    "description" => "Defense specialist with quick recovery..."
                ]
            ];
        }
        
        // Singles rackets
        else if (strpos($q, "vợt") !== false && (strpos($q, "đơn") !== false || strpos($q, "singles") !== false)) {
            $products = [
                [
                    "id" => 9,
                    "name" => "Vợt Yonex Nanoflare 800",
                    "price" => 2500000,
                    "original_price" => 2700000,
                    "image" => "http://localhost:8000/uploads/products/yonex-nanoflare800.jpg",
                    "brand" => "Yonex",
                    "description" => "Speed demon cho singles domination..."
                ],
                [
                    "id" => 25,
                    "name" => "Vợt Lining Bladex 900",
                    "price" => 1900000,
                    "original_price" => 2200000,
                    "image" => "http://localhost:8000/uploads/products/lining-bladex900.jpg",
                    "brand" => "Lining",
                    "description" => "Precision control cho singles game..."
                ]
            ];
        }
        
        // Doubles rackets
        else if (strpos($q, "vợt") !== false && (strpos($q, "đôi") !== false || strpos($q, "doubles") !== false || strpos($q, "cặp") !== false)) {
            $products = [
                [
                    "id" => 26,
                    "name" => "Vợt Yonex Astrox 88S Pro (Front Court)",
                    "price" => 2100000,
                    "original_price" => 2400000,
                    "image" => "http://localhost:8000/uploads/products/yonex-astrox88s.jpg",
                    "brand" => "Yonex",
                    "description" => "Perfect for doubles front court player..."
                ],
                [
                    "id" => 27,
                    "name" => "Vợt Yonex Astrox 88D Pro (Back Court)",
                    "price" => 2100000,
                    "original_price" => 2400000,
                    "image" => "http://localhost:8000/uploads/products/yonex-astrox88d.jpg",
                    "brand" => "Yonex",
                    "description" => "Ideal for doubles back court power..."
                ]
            ];
        }
        
        // General racket queries
        else if (strpos($q, "vợt") !== false) {
            $products = [
                [
                    "id" => 3,
                    "name" => "Vợt Yonex Astrox 100ZZ",
                    "price" => 3200000,
                    "original_price" => 3500000,
                    "image" => "http://localhost:8000/uploads/products/yonex-astrox100zz.jpg",
                    "brand" => "Yonex",
                    "description" => "Flagship premium racket..."
                ],
                [
                    "id" => 9,
                    "name" => "Vợt Yonex Nanoflare 800",
                    "price" => 2500000,
                    "original_price" => 2700000,
                    "image" => "http://localhost:8000/uploads/products/yonex-nanoflare800.jpg",
                    "brand" => "Yonex",
                    "description" => "Speed & agility specialist..."
                ],
                [
                    "id" => 1,
                    "name" => "Vợt Yonex Muscle Power 29 Light",
                    "price" => 85000,
                    "original_price" => 95000,
                    "image" => "http://localhost:8000/uploads/products/yonex-mp29l.jpg",
                    "brand" => "Yonex",
                    "description" => "Affordable entry-level option..."
                ]
            ];
        }
        
        // === PRICE QUERIES ===
        
        // Most expensive products
                    "image" => "http://localhost:8000/uploads/products/yonex-arcsaber11pro.jpg",
                    "brand" => "Yonex",
                    "description" => "Legendary control & touch racket..."
                ],
                [
                    "id" => 24,
                    "name" => "Vợt Victor Jetspeed S10",
                    "price" => 1800000,
                    "original_price" => 2000000,
                    "image" => "http://localhost:8000/uploads/products/victor-jetspeed-s10.jpg",
                    "brand" => "Victor",
                    "description" => "Defense specialist với precision..."
                ]
            ];
        }
        
        // Singles rackets  
        else if (strpos($q, "vợt") !== false && (strpos($q, "đơn") !== false || strpos($q, "singles") !== false)) {
            $products = [
                [
                    "id" => 9,
                    "name" => "Vợt Yonex Nanoflare 800",
                    "price" => 2500000,
                    "original_price" => 2700000,
                    "image" => "http://localhost:8000/uploads/products/yonex-nanoflare800.jpg",
                    "brand" => "Yonex",
                    "description" => "Speed demon cho singles mastery..."
                ],
                [
                    "id" => 10,
                    "name" => "Vợt Yonex Astrox 88D Pro",
                    "price" => 2100000,
                    "original_price" => 2400000,
                    "image" => "http://localhost:8000/uploads/products/yonex-astrox88d.jpg",
                    "brand" => "Yonex",
                    "description" => "All-court versatility cho singles..."
                ]
            ];
        }
        
        // Doubles rackets
        else if (strpos($q, "vợt") !== false && (strpos($q, "đôi") !== false || strpos($q, "doubles") !== false || strpos($q, "cặp") !== false)) {
            $products = [
                [
                    "id" => 25,
                    "name" => "Vợt Yonex Nanoflare 700 (Front)",
                    "price" => 1900000,
                    "original_price" => 2100000,
                    "image" => "http://localhost:8000/uploads/products/yonex-nanoflare700.jpg",
                    "brand" => "Yonex",
                    "description" => "Front court specialist racket..."
                ],
                [
                    "id" => 8,
                    "name" => "Vợt Yonex Astrox 99 Pro (Back)",
                    "price" => 2800000,
                    "original_price" => 3100000,
                    "image" => "http://localhost:8000/uploads/products/yonex-astrox99pro.jpg",
                    "brand" => "Yonex",
                    "description" => "Back court power specialist..."
                ]
            ];
        }
        
        // General racket query
        else if (strpos($q, "vợt") !== false) {
            $products = [
                [
                    "id" => 3,
                    "name" => "Vợt Yonex Astrox 100ZZ",
                    "price" => 3200000,
                    "original_price" => 3500000,
                    "image" => "http://localhost:8000/uploads/products/yonex-astrox100zz.jpg",
                    "brand" => "Yonex",
                    "description" => "Flagship premium racket..."
                ],
                [
                    "id" => 1,
                    "name" => "Vợt Yonex Muscle Power 29 Light",
                    "price" => 85000,
                    "original_price" => 95000,
                    "image" => "http://localhost:8000/uploads/products/yonex-mp29l.jpg",
                    "brand" => "Yonex",
                    "description" => "Entry-level quality racket..."
                ]
            ];
        }
                // Sản phẩm đắt nhất
        else if (strpos($q, "dat nhat") !== false || strpos($q, "expensive") !== false) {
            try {
                $dbProducts = \App\Models\Product::where('Status', 1)
                    ->orderBy('Price', 'desc')
                    ->take(3)
                    ->get();
                    
                if ($dbProducts->count() > 0) {
                    return $this->formatProducts($dbProducts);
                }
            } catch (\Exception $e) {
                \Log::error('Database query error: ' . $e->getMessage());
            }
            
            // Fallback data
            $products = [
                [
                    "id" => 3,
                    "name" => "Vợt Yonex Astrox 100ZZ",
                    "price" => 3500000,
                    "original_price" => 3800000,
                    "image" => "http://localhost:8000/uploads/products/yonex-astrox100zz.jpg",
                    "brand" => "Yonex",
                    "description" => "Flagship Astrox series - đỉnh cao công nghệ..."
                ],
                [
                    "id" => 6,
                    "name" => "Giày Yonex Aerus Z2",
                    "price" => 2550000,
                    "original_price" => 2800000,
                    "image" => "http://localhost:8000/uploads/products/yonex-aerusz2.jpg",
                    "brand" => "Yonex",
                    "description" => "Giày cao cấp siêu nhẹ cho VĐV chuyên nghiệp..."
                ]
            ];
        }
        // Sản phẩm rẻ nhất
        else if (strpos($q, "re nhat") !== false || strpos($q, "gia re") !== false) {
            try {
                $dbProducts = \App\Models\Product::where('Status', 1)
                    ->where('Price', '>', 0)
                    ->orderBy('Price', 'asc')
                    ->take(3)
                    ->get();
                    
                if ($dbProducts->count() > 0) {
                    return $this->formatProducts($dbProducts);
                }
            } catch (\Exception $e) {
                \Log::error('Database query error: ' . $e->getMessage());
            }
            
            // Fallback data
            $products = [
                [
                    "id" => 1,
                    "name" => "Vợt Yonex Muscle Power 29L",
                    "price" => 85000,
                    "original_price" => 95000,
                    "image" => "http://localhost:8000/uploads/products/yonex-mp29l.jpg",
                    "brand" => "Yonex",
                    "description" => "Vợt phổ thông chất lượng tốt..."
                ]
            ];
        }
        // Giày cầu lông
        else if (strpos($q, "giày") !== false) {
            $products = [
                [
                    "id" => 5,
                    "name" => "Giày Yonex Aerus Z2",
                    "price" => 2550000,
                    "original_price" => 2800000,
                    "image" => "http://localhost:8000/uploads/products/yonex-aerusz2.jpg",
                    "brand" => "Yonex",
                    "description" => "Giày cầu lông cao cấp, siêu nhẹ..."
                ]
            ];
        }
        // Sản phẩm đắt nhất
        else if (strpos($q, "đắt") !== false || strpos($q, "cao cấp") !== false) {
            $products = [
                [
                    "id" => 3,
                    "name" => "Vợt Yonex Astrox 100ZZ",
                    "price" => 3200000,
                    "original_price" => 3500000,
                    "image" => "http://localhost:8000/uploads/products/yonex-astrox100zz.jpg",
                    "brand" => "Yonex",
                    "description" => "Vợt cao cấp nhất của Yonex..."
                ],
                [
                    "id" => 7,
                    "name" => "Giày Yonex Power Cushion Infinity",
                    "price" => 1200000,
                    "original_price" => 1350000,
                    "image" => "http://localhost:8000/uploads/products/yonex-infinity.jpg",
                    "brand" => "Yonex",
                    "description" => "Giày cao cấp nhất với công nghệ tiên tiến..."
                ]
            ];
        }
        // Sản phẩm rẻ nhất
        else if (strpos($q, "rẻ") !== false || strpos($q, "tiết kiệm") !== false) {
            $products = [
                [
                    "id" => 1,
                    "name" => "Vợt Yonex Muscle Power 29 Light",
                    "price" => 85000,
                    "original_price" => 95000,
                    "image" => "http://localhost:8000/uploads/products/yonex-mp29l.jpg",
                    "brand" => "Yonex",
                    "description" => "Vợt giá rẻ chất lượng tốt..."
                ]
            ];
        }
        // Default: sản phẩm nổi bật
        else {
            $products = [
                [
                    "id" => 3,
                    "name" => "Vợt Yonex Astrox 100ZZ",
                    "price" => 3200000,
                    "original_price" => 3500000,
                    "image" => "http://localhost:8000/uploads/products/yonex-astrox100zz.jpg",
                    "brand" => "Yonex",
                    "description" => "Vợt tấn công hàng đầu..."
                ],
                [
                    "id" => 5,
                    "name" => "Giày Yonex Power Cushion Aerus Z",
                    "price" => 850000,
                    "original_price" => 950000,
                    "image" => "http://localhost:8000/uploads/products/yonex-aerus-z.jpg",
                    "brand" => "Yonex",
                    "description" => "Giày siêu nhẹ chuyên nghiệp..."
                ]
            ];
        }
        
        return $products;
    }
}