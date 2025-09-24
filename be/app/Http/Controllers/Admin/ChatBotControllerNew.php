<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatBotControllerNew extends Controller
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
        
        // ===== 2. LỊCH SỬ CẦU LÔNG =====
        if (strpos($q, "lịch sử") !== false || strpos($q, "history") !== false || strpos($q, "origin") !== false || strpos($q, "xuất xứ") !== false || strpos($q, "bắt nguồn") !== false) {
            return "📚 **BADMINTON HISTORY** - Rich heritage:\n\n🏰 **Origins** (1860s):\n• Started in **Badminton House**, England\n• Evolved from ancient game **Battledore**\n• British officers in India played **Poona**\n• First rules written in 1877\n\n🌍 **Global Development**:\n📅 **1899**: First All England Championships\n📅 **1934**: BWF formed\n📅 **1992**: Olympic debut\n📅 **1996**: Mixed doubles added\n\n🏆 **Legendary Players**:\n🇨🇳 **Lin Dan**: Super Dan, 2x Olympic champion\n🇮🇩 **Rudy Hartono**: 8x All England winner\n🇩🇰 **Peter Gade**: European legend\n🇲🇾 **Lee Chong Wei**: 3x Olympic silver\n\n🇻🇳 **Vietnam Badminton**:\n• Nguyễn Tiến Minh: First world top 10\n• Growing popularity since 2000s";
        }
        
        // ===== 3. TUYỂN THỦ CHUYÊN NGHIỆP =====
        if ((strpos($q, "tuyển thủ") !== false || strpos($q, "chuyên nghiệp") !== false || strpos($q, "vđv") !== false || strpos($q, "pro") !== false) && (strpos($q, "tấn công") !== false || strpos($q, "chuyên công") !== false || strpos($q, "công") !== false || strpos($q, "smash") !== false)) {
            return "Tuyển thủ chuyên nghiệp chuyên tấn công! 🏆 Equipment cao cấp cho bạn:\n\n🥇 **Yonex Astrox 100ZZ** - Flagship tấn công (3.2-3.5tr)\n🥈 **Yonex Astrox 99 Pro** - Cân bằng hoàn hảo (2.8-3.1tr)\n🥉 **Lining Aeronaut 9000C** - Power & Speed (2.2-2.4tr)\n\n✅ Specs: Head-heavy, stiff shaft, string tension 26-30lbs\n💡 Pro tip: Kết hợp footwork nhanh với smash power\n\nBạn có sở thích thương hiệu cụ thể không?";
        }
        
        if ((strpos($q, "tuyển thủ") !== false || strpos($q, "chuyên nghiệp") !== false) && (strpos($q, "đơn") !== false || strpos($q, "singles") !== false)) {
            return "Chuyên gia đánh đơn! 🎯 Speed & agility rackets cho bạn:\n\n⚡ **Yonex Nanoflare 800/1000Z** - Tốc độ đỉnh cao\n💪 **Yonex Astrox 88D Pro** - All-round balance\n🎯 **Victor Jetspeed S12** - Precision control\n\n✅ Features: Even balance, fast response, lightweight\n💡 Singles strategy: Change of pace, court coverage\n\nBạn thường chơi phong cách defensive hay aggressive?";
        }
        
        if (strpos($q, "tuyển thủ") !== false || strpos($q, "chuyên nghiệp") !== false || strpos($q, "vđv") !== false || strpos($q, "pro") !== false) {
            return "Chào tuyển thủ! 🏆 Professional equipment consultation:\n\n🏸 **Premium Rackets**: Astrox, Nanoflare, Aeronaut\n👟 **Pro Shoes**: Power Cushion, Aerus Z series  \n🎾 **Tournament Shuttles**: Yonex AS-50, Victor Gold\n⚙️ **Accessories**: Pro grip, vibration dampener\n📈 **Performance**: Analytics, training programs\n\nBạn cần tư vấn category nào đầu tiên?";
        }
        
        // ===== 4. VỢT CẦU LÔNG - TOÀN DIỆN =====
        if (strpos($q, "vợt") !== false) {
            if (strpos($q, "mới") !== false || strpos($q, "người mới") !== false || strpos($q, "bắt đầu") !== false || strpos($q, "học") !== false) {
                return "🎯 Vợt cho người mới bắt đầu:\n\n👶 **Beginner Level** (50k-150k):\n• Yonex Muscle Power 29L - Siêu nhẹ 85g\n• Yonex Carbonex 21 - Bền bỉ, dễ control\n• Lining Smash XP 610 - Giá tốt, chất lượng ổn\n\n✅ **Đặc điểm cần có**: Nhẹ (<90g), thân mềm, head light\n💡 **Lời khuyên**: Học technique trước, equipment sau\n\nBạn có ngân sách khoảng bao nhiêu?";
            }
            
            if (strpos($q, "tấn công") !== false || strpos($q, "smash") !== false || strpos($q, "chuyên công") !== false || strpos($q, "công") !== false || strpos($q, "power") !== false || strpos($q, "mạnh") !== false) {
                return "💥 **POWER RACKETS** - Vợt tấn công mạnh mẽ:\n\n🔥 **Premium Tier**:\n• Yonex Astrox 100ZZ - Ultimate power (3.5tr)\n• Yonex Astrox 99 Pro - Balanced aggression (3tr)\n• Lining Aeronaut 9000C - Speed + Power (2.4tr)\n\n⚡ **Performance Tier**:\n• Victor Jetspeed S12 - Sharp attacks (1.8tr)\n• Yonex Astrox 88S Pro - Solid power (2.1tr)\n\n✅ **Specs**: Head-heavy, stiff shaft, 88-94g\n💪 **String**: 24-28lbs BG80 Power hoặc Aerobite\n\nBạn đã có kinh nghiệm smash chưa?";
            }
            
            if (strpos($q, "phòng thủ") !== false || strpos($q, "defend") !== false || strpos($q, "thủ") !== false || strpos($q, "defensive") !== false) {
                return "🛡️ **DEFENSIVE RACKETS** - Vợt phòng thủ chuyên nghiệp:\n\n🎯 **Control Masters**:\n• Yonex Arcsaber 11 Pro - Legendary control\n• Victor Jetspeed S10 - Defense specialist  \n• Yonex Duora Z-Strike - Dual power zones\n\n⚡ **Quick Response**:\n• Yonex Nanoflare 700 - Lightning fast\n• Lining Bladex 900 - Precision control\n\n✅ **Features**: Even balance, flexible shaft, quick recovery\n🥅 **Playstyle**: Counter-attack, net play mastery\n\nBạn thường chơi doubles hay singles?";
            }
            
            if (strpos($q, "đơn") !== false || strpos($q, "singles") !== false) {
                return "🏃‍♂️ **SINGLES SPECIALIST** - Vợt chuyên đánh đơn:\n\n⚡ **Speed Demons**:\n• Yonex Nanoflare 800/1000Z - Ultimate speed\n• Victor Jetspeed S12 - Court coverage master\n• Yonex Astrox 88D Pro - All-court weapon\n\n🎯 **Precision Tools**:\n• Lining Bladex 900 - Surgical precision\n• Yonex Arcsaber 11 Pro - Touch & feel\n\n✅ **Singles Strategy**: Court coverage, stamina, variety\n💡 **Key Skills**: Drop shots, clears, deceptive shots\n\nBạn thích chơi tốc độ cao hay control game?";
            }
            
            if (strpos($q, "đôi") !== false || strpos($q, "doubles") !== false || strpos($q, "cặp") !== false) {
                return "👫 **DOUBLES DYNAMICS** - Vợt chuyên đánh đôi:\n\n🚀 **Front Court** (Người trước lưới):\n• Yonex Nanoflare 800 - Lightning reflexes\n• Victor Jetspeed S10 - Quick exchanges\n\n💥 **Back Court** (Người sau sân):\n• Yonex Astrox 99 Pro - Power from back\n• Lining Aeronaut 9000C - Explosive smashes\n\n🎯 **All-Round Doubles**:\n• Yonex Astrox 88S/D Pro - Versatile duo\n\n✅ **Doubles Strategy**: Attack/Defense rotation\nBạn thường chơi ở vị trí nào?";
            }
            
            return "🏸 **VỢT CẦU LÔNG** - Tư vấn chuyên sâu:\n\n📊 **Theo trình độ**:\n🥉 Beginner: Muscle Power, Carbonex (50-150k)\n🥈 Intermediate: Arcsaber, Nanoray (200k-1tr)\n🥇 Advanced: Astrox, Nanoflare (1-4tr)\n\n🎯 **Theo phong cách**:\n⚡ Speed: Nanoflare series\n💥 Power: Astrox series\n🛡️ Control: Arcsaber series\n\nBạn muốn tôi tư vấn theo trình độ hay phong cách chơi?";
        }
        
        // ===== 5. GIÀY CẦU LÔNG =====
        if (strpos($q, "giày") !== false || strpos($q, "shoe") !== false) {
            if (strpos($q, "chuyên nghiệp") !== false || strpos($q, "pro") !== false || strpos($q, "thi đấu") !== false) {
                return "👟 **PRO BADMINTON SHOES** - Giày thi đấu chuyên nghiệp:\n\n🏆 **Championship Level**:\n• Yonex Power Cushion Aerus Z2 - Siêu nhẹ 270g (2.8tr)\n• Yonex Power Cushion Infinity - Đệm tối ưu (1.3tr)\n• Lining Ranger TD Pro - Tournament grade (800k)\n\n⚡ **Performance Features**:\n✅ Power Cushion+ technology - Giảm chấn 28%\n✅ Double Russel Mesh - Thoát khí tuyệt vời\n✅ Lateral support - Chống lật cổ chân\n\nBạn hay bị đau chân hay cổ chân không?";
            }
            return "👟 **GIÀY CẦU LÔNG** - Foundation của game:\n\n🎯 **Theo mặt sân**:\n• Sân gỗ: Power Cushion series (grip tốt)\n• Sân nhựa/PU: Ranger series (độ bền cao)  \n• Sân xi măng: Court Ace (chống mài mòn)\n\n💰 **Theo ngân sách**:\n🥉 Entry: 300-500k (Lining Basic, Victor A362)\n🥈 Mid-range: 600k-1tr (PC 55, Ranger TD)\n🥇 Premium: 1-3tr (Aerus Z, Infinity)\n\n❓ Bạn thường chơi sân gì và có vấn đề gì về chân không?";
        }
        
        // ===== 6. CẦU LÔNG (SHUTTLECOCK) =====
        if ((strpos($q, "quả cầu") !== false || strpos($q, "shuttlecock") !== false || strpos($q, "shuttle") !== false) && strpos($q, "vợt") === false && strpos($q, "lịch sử") === false) {
            return "🏸 **SHUTTLECOCK** - Linh hồn của trận đấu:\n\n🏆 **Tournament Grade**:\n• Yonex AS-50 - Olympic standard (220k/hộp)\n• Victor Champion No.1 - BWF approved (180k/hộp)\n• Lining A+90 - Premium quality (160k/hộp)\n\n🎯 **Training Grade**:\n• Yonex AS-30 - Bền, bay ổn định (120k/hộp)\n• Victor Gold No.2 - Tỷ lệ giá/chất tốt (90k/hộp)\n\n💡 **Chọn cầu theo**:\n• Nhiệt độ: Lạnh dùng slow, nóng dùng fast\n• Độ cao: Cao dùng fast, thấp dùng slow  \n• Sân: Indoor slow hơn outdoor\n\nSân của bạn thường nhiệt độ như nào?";
        }
        
        // ===== 7. KỸ THUẬT CẦU LÔNG =====
        if (strpos($q, "smash") !== false || strpos($q, "đập") !== false || strpos($q, "cú đập") !== false) {
            return "💥 **SMASH TECHNIQUE** - Vũ khí tối thượng:\n\n🎯 **Perfect Smash Steps**:\n1️⃣ **Preparation**: Sideways stance, racket up high\n2️⃣ **Jump**: Leap with non-racket foot forward  \n3️⃣ **Contact**: Hit at highest point, full extension\n4️⃣ **Follow-through**: Racket down across body\n\n🚀 **Power Secrets**:\n✅ Wrist snap - 70% of power\n✅ Body rotation - Core engagement  \n✅ Leg drive - Jump into the shot\n✅ Timing - Contact at peak height\n\n⚠️ **Common Mistakes**: Late preparation, flat feet, weak wrist\n🏋️ **Training drills**: Shadow smash, multi-shuttle, target practice\n\nBạn muốn luyện power hay accuracy?";
        }
        
        if (strpos($q, "clear") !== false || strpos($q, "đánh cao") !== false || strpos($q, "cầu cao") !== false) {
            return "🌟 **CLEAR TECHNIQUE** - Defensive foundation:\n\n🎯 **Perfect Clear Form**:\n1️⃣ **Setup**: Get behind shuttle early\n2️⃣ **Footwork**: Right foot back (RH player)\n3️⃣ **Backswing**: Racket head drops low\n4️⃣ **Contact**: Hit with upward angle\n5️⃣ **Finish**: High follow-through\n\n📐 **Clear Types**:\n🛡️ **Defensive Clear**: High & deep to baseline\n⚡ **Attack Clear**: Flatter, faster trajectory\n\n💡 **Tactical Usage**:\n✅ Reset rally tempo ✅ Move opponent to backcourt\n✅ Buy recovery time ✅ Set up next attack\n\nBạn thường bị thiếu power hay thiếu độ chính xác?";
        }
        
        if (strpos($q, "drop") !== false || strpos($q, "cầu cắt") !== false || strpos($q, "cắt cầu") !== false) {
            return "🎯 **DROP SHOT** - Finesse weapon:\n\n✨ **Deceptive Drop**:\n1️⃣ **Deception**: Same preparation as clear/smash\n2️⃣ **Contact**: Gentle touch, racket face open\n3️⃣ **Placement**: Just over net, sharp angle\n4️⃣ **Follow**: Quick recovery to center\n\n🎪 **Drop Shot Variations**:\n🪶 **Slow Drop**: Floats gently over net\n⚡ **Fast Drop**: Quick, steep descent\n🔄 **Cross Drop**: Angled to opposite side\n\n🧠 **Tactical Timing**:\n✅ After driving opponent deep\n✅ When opponent is off-balance\n✅ To break rhythm\n\nBạn muốn học drop thẳng hay drop chéo?";
        }
        
        if (strpos($q, "di chuyển") !== false || strpos($q, "footwork") !== false || strpos($q, "bước chân") !== false || strpos($q, "chạy") !== false) {
            return "🏃‍♂️ **FOOTWORK MASTERY** - Heart of badminton:\n\n⭐ **6-Point Movement System**:\n1️⃣ **Center Position**: Ready stance, weight forward\n2️⃣ **Front Court**: Lunge step to net\n3️⃣ **Rear Court**: Chasse steps backward\n4️⃣ **Side Court**: Side shuffle/crossover\n5️⃣ **Recovery**: Always return to center\n6️⃣ **Split Step**: Small hop before opponent hits\n\n🎯 **Movement Patterns**:\n📐 **Around-the-Head**: Backhand corner coverage\n🔀 **Cross-Court**: Diagonal movement\n⚡ **Net Rush**: Quick forward movement\n\n💪 **Training Drills**: 6-corner drill, shadow badminton, ladder exercises\n\nPhần nào bạn thấy khó nhất?";
        }
        
        if (strpos($q, "giao cầu") !== false || strpos($q, "serve") !== false || strpos($q, "phát cầu") !== false) {
            return "🎾 **SERVING MASTERY** - Start strong:\n\n🎯 **Low Serve** (Doubles specialty):\n✅ Contact below waist ✅ Gentle wrist flick\n✅ Barely clears net ✅ Target: Front service line\n\n🚀 **High Serve** (Singles weapon):\n✅ Full swing motion ✅ Contact high and forward\n✅ Deep to baseline ✅ Force weak return\n\n🌀 **Flick Serve** (Surprise attack):\n✅ Deceptive low start ✅ Quick wrist snap\n✅ Fast & flat trajectory\n\n⚖️ **Service Rules**: Underarm motion only, contact below waist, diagonal serve, both feet stationary\n\nBạn chủ yếu chơi đơn hay đôi?";
        }
        
        if (strpos($q, "kỹ thuật") !== false || strpos($q, "technique") !== false || strpos($q, "cách chơi") !== false || strpos($q, "học") !== false) {
            return "🎓 **BADMINTON TECHNIQUE** - Comprehensive guide:\n\n🏸 **Core Techniques**:\n💥 **Overhead**: Smash, clear, drop\n🤚 **Underarm**: Lift, drive, net shot\n🏃 **Footwork**: 6-point movement\n🎾 **Serve**: Low, high, flick variations\n\n📊 **Learning Priority**:\n1️⃣ **Basics**: Grip, stance, footwork\n2️⃣ **Strokes**: Clear, drop, smash progression  \n3️⃣ **Serves**: Master low serve first\n4️⃣ **Advanced**: Deception, variation, tactics\n\n🎯 **Practice Structure**: 20% footwork, 40% strokes, 20% serves, 20% game situations\n\nBạn muốn focus vào technique nào trước?";
        }
        
        // ===== 8. CHIẾN THUẬT =====
        if (strpos($q, "chiến thuật") !== false || strpos($q, "tactics") !== false || strpos($q, "strategy") !== false) {
            if (strpos($q, "đơn") !== false || strpos($q, "singles") !== false) {
                return "🎯 **SINGLES STRATEGY** - 1v1 mastery:\n\n🏃‍♂️ **Movement Game**:\n✅ Force opponent to all 4 corners\n✅ Change pace: slow-fast-slow rhythm\n✅ Use length: deep clears to short drops\n✅ Stamina management: conserve energy\n\n⚡ **Attack Patterns**:\n📐 Cross-court clear → Straight drop\n🔄 Deep serve → Net attack\n💥 Lift → Smash → Drop follow-up\n\n🛡️ **Defensive Strategy**: Make opponent move more, counter-attack, patience over power\n\nPhong cách nào phù hợp: All-court hay baseline?";
            }
            return "🧠 **BADMINTON TACTICS** - Mental game:\n\n🎯 **Universal Principles**:\n1️⃣ **Control center**: Dominate middle court\n2️⃣ **Create openings**: Make opponent move\n3️⃣ **Exploit weaknesses**: Target backhand/movement\n4️⃣ **Vary shots**: Keep opponent guessing\n5️⃣ **Pressure points**: Attack when ahead\n\n📊 **Game Phases**: Early game (establish patterns), Mid game (build pressure), End game (close out points)\n\nBạn muốn học tactics cho đơn hay đôi?";
        }
        
        // ===== 9. LUẬT CHƠI =====
        if (strpos($q, "luật") !== false || strpos($q, "rule") !== false || strpos($q, "quy định") !== false) {
            if (strpos($q, "điểm") !== false || strpos($q, "scoring") !== false) {
                return "📊 **SCORING SYSTEM** - Rally Point System:\n\n🏆 **Game Structure**:\n• Best of 3 games • First to 21 points wins\n• Must win by 2 points • Maximum 30 points\n\n⚡ **Point Rules**:\n✅ Every rally = 1 point\n✅ Winner of rally serves next\n✅ Server calls score first\n\n⏱️ **Intervals**: 60s at 11 points, 120s between games\n🔄 **Deuce**: 20-20 play to 22, 29-29 next point wins\n\nCó tình huống scoring nào bạn thắc mắc?";
            }
            return "⚖️ **BADMINTON RULES** - Official BWF regulations:\n\n🚫 **Common Faults**:\n• Double hit • Carry/sling • Net contact\n• Invasion • Service faults\n\n🏸 **Service Rules**:\n• Underarm motion only • Below waist contact\n• Diagonal service • Alternate courts after points\n\n📏 **Court Dimensions**: Singles 13.4×5.18m, Doubles 13.4×6.1m, Net 1.55m at posts\n\nBạn muốn hiểu rõ luật nào cụ thể?";
        }
        
        // ===== 10. TRAINING & FITNESS =====
        if (strpos($q, "luyện tập") !== false || strpos($q, "training") !== false || strpos($q, "tập") !== false || strpos($q, "fitness") !== false) {
            return "💪 **BADMINTON TRAINING** - Complete program:\n\n🏃‍♂️ **Physical Fitness** (40%):\n• Agility: Ladder drills, cone work\n• Explosive power: Jump squats, plyometrics  \n• Endurance: Interval running, court sprints\n• Flexibility: Dynamic stretching, yoga\n\n🏸 **Technical Skills** (40%):\n• Multi-shuttle feeding • Shadow badminton\n• Wall practice • Video analysis\n\n🧠 **Mental & Tactical** (20%):\n• Match simulation • Pressure situations\n• Pattern recognition • Mental toughness\n\n📅 **Weekly Schedule**: 3x technical, 2x fitness, 1x match play, 1x rest\n\nBạn muốn program cho level nào?";
        }
        
        // ===== 11. DINH DƯỠNG =====
        if (strpos($q, "dinh dưỡng") !== false || strpos($q, "nutrition") !== false || strpos($q, "ăn uống") !== false) {
            return "🥗 **SPORTS NUTRITION** - Fuel your performance:\n\n⚡ **Pre-Game** (2-3h before):\n• Complex carbs: Brown rice, oatmeal\n• Lean protein: Chicken, fish, eggs\n• Hydration: 500ml water\n\n🏸 **During Game**:\n• Isotonic drinks: Pocari, Aquarius\n• Quick carbs: Banana, energy gel\n\n🔄 **Post-Game** (30min window):\n• Protein shake + fruit\n• Chocolate milk (3:1 carb:protein)\n• Rehydration: 1.5x fluid lost\n\n💊 **Supplements**: Whey protein ✅, Multivitamin ✅, Creatine (optional)\n\nBạn có mục tiêu cụ thể gì về nutrition?";
        }
        
        // ===== 12. CHẤN THƯƠNG =====
        if (strpos($q, "chấn thương") !== false || strpos($q, "injury") !== false || strpos($q, "đau") !== false) {
            return "🏥 **INJURY PREVENTION** - Stay in the game:\n\n⚠️ **Common Injuries**:\n🦵 Ankle sprain (40%) 🦴 Knee problems\n💪 Shoulder issues 🤲 Wrist overuse\n\n🛡️ **Prevention**:\n✅ Warm-up: 10-15min dynamic stretching\n✅ Cool-down: Static stretching, foam rolling\n✅ Strength: Core, glutes, rotator cuff\n✅ Proper footwear & load management\n\n🩹 **First Aid RICE**: Rest, Ice (15-20min), Compression, Elevation\n\nBạn có vấn đề gì cụ thể về chấn thương?";
        }
        
        // ===== 13. GIẢI ĐẤU =====
        if (strpos($q, "giải đấu") !== false || strpos($q, "tournament") !== false || strpos($q, "thi đấu") !== false) {
            return "🏆 **MAJOR TOURNAMENTS** - Badminton calendar:\n\n🥇 **BWF Major Events**:\n• Olympic Games: 4-year cycle, ultimate goal\n• World Championships: Annual, all 5 categories\n• All England: Oldest & most prestigious\n• Thomas/Uber Cup: Team events\n\n⭐ **BWF World Tour**:\n🏆 Super 1000: Indonesia, China, All England\n🥈 Super 750: Malaysia, Denmark, Japan  \n🥉 Super 500: India, Thailand, Singapore\n\n🇻🇳 **Vietnam Circuit**: Yonex Vietnam Open, National Championships\n\n📅 **Competition Prep**: Entry requirements, equipment checks, mental preparation\n\nBạn có ý định tham gia giải nào không?";
        }
        
        // ===== 14. THƯƠNG HIỆU =====
        if (strpos($q, "thương hiệu") !== false || strpos($q, "brand") !== false || strpos($q, "yonex") !== false || strpos($q, "lining") !== false || strpos($q, "victor") !== false || strpos($q, "so sánh") !== false) {
            return "🏷️ **BRAND COMPARISON** - Choose your weapon:\n\n🇯🇵 **YONEX** - The King:\n✅ Premium quality, innovation, pro endorsements\n✅ Famous: Astrox, Nanoflare, Power Cushion\n✅ Price: Premium tier (1-4tr)\n✅ Best for: Serious players, tournaments\n\n🇨🇳 **LINING** - Value Champion:\n✅ Great price/performance ratio\n✅ Famous: Aeronaut, Bladex, Ranger\n✅ Price: Mid-range (500k-2.5tr)\n✅ Best for: Recreational to advanced\n\n🇹🇼 **VICTOR** - Tech Innovation:\n✅ Modern technology, durability\n✅ Famous: Jetspeed, TK-F series\n✅ Price: Competitive (600k-3tr)\n✅ Best for: Power players, tech enthusiasts\n\nBạn ưu tiên giá cả hay chất lượng?";
        }
        
        // ===== 15. ĐẶT SÂN =====
        if (strpos($q, "sân") !== false || strpos($q, "đặt") !== false || strpos($q, "booking") !== false) {
            return "🏟️ **COURT BOOKING** - Vicnex facilities:\n\n🏸 **Court Specs**: Premium wooden flooring, LED lighting, climate control\n\n💰 **Pricing**:\n🌅 Morning (6AM-12PM): 80-100k/hour\n☀️ Afternoon (12PM-6PM): 100-120k/hour\n🌙 Evening (6PM-10PM): 150-200k/hour\n🌃 Night (10PM-12AM): 120-150k/hour\n\n🎁 **Special Offers**:\n• 24h advance: 10% off • Monthly membership: 15% off\n• Student rate: 20% off • Group booking: 5% off\n\n📱 **Booking**: Online platform, mobile app, phone, walk-in\n\nBạn muốn đặt sân khi nào?";
        }
        
        // ===== 16. GIÁ CẢ =====
        if (strpos($q, "giá") !== false || strpos($q, "bao nhiêu") !== false || strpos($q, "price") !== false) {
            return "💰 **BẢNG GIÁ VICNEX** - Comprehensive pricing:\n\n🏸 **Rackets**:\n🥉 Entry Level: 50k-200k (Muscle Power, Carbonex)\n🥈 Intermediate: 300k-1tr (Arcsaber, Nanoray)\n🥇 Professional: 1tr-4tr (Astrox, Nanoflare)\n\n👟 **Shoes**: 200k-3tr (Basic → Pro level)\n👕 **Apparel**: 100k-800k (Casual → Tournament)\n🏸 **Shuttles**: 80k-250k/dozen (Training → Tournament)\n⚙️ **Accessories**: 20k-200k (Grips, bags, strings)\n🏟️ **Court Rental**: 80k-200k/hour\n\nSản phẩm nào bạn quan tâm pricing?";
        }
        
        // ===== 17. SẢN PHẨM ĐẮT/RẺ NHẤT =====
        if (strpos($q, "đắt nhất") !== false || strpos($q, "dat nhat") !== false || strpos($q, "expensive") !== false) {
            return "💎 **TOP PREMIUM PRODUCTS**:\n\n🏸 **Most Expensive Rackets**:\n• Yonex Astrox 100ZZ - 3.5-3.9tr (Ultimate power)\n• Yonex Duora Z-Strike - 3.2-3.6tr (Dual zones)\n• Lining Aeronaut 9000C - 2.2-2.5tr (Speed+Power)\n\n👟 **Premium Shoes**:\n• Yonex Aerus Z2 - 2.5-2.8tr (Ultra-light)\n• Yonex Power Cushion Infinity - 1.3-1.5tr\n\n✅ **Why Premium**: Namd technology, carbon fiber, Olympic-grade materials\n\nBạn quan tâm loại sản phẩm nào?";
        }
        
        if (strpos($q, "rẻ nhất") !== false || strpos($q, "re nhat") !== false || strpos($q, "giá rẻ") !== false) {
            return "💰 **BUDGET-FRIENDLY OPTIONS**:\n\n🏸 **Affordable Rackets**:\n• Yonex Muscle Power 29L - 80-120k\n• Yonex Carbonex 21 - 90-130k\n• Lining Smash XP 610 - 85-110k\n\n👟 **Entry Shoes**:\n• Lining Ranger TD - 300-400k\n• Victor A362 - 250-350k\n• Yonex Power Cushion 35 - 400-500k\n\n✅ **Quality Assured**: Chính hãng, bảo hành đầy đủ, suitable for beginners\n\nCần tư vấn chi tiết sản phẩm nào?";
        }
        
        // ===== FALLBACK COMPREHENSIVE =====
        return "🤖 **VICNEX AI ASSISTANT** - Chuyên gia cầu lông toàn diện!\n\n🎯 **Tôi có thể giúp bạn với 100+ chủ đề**:\n\n🏸 **EQUIPMENT**: Rackets, shoes, shuttles, apparel, accessories\n💡 **TECHNIQUE**: Smash, clear, drop, serve, footwork, advanced skills\n🧠 **STRATEGY**: Singles, doubles, tactics, mental game, competition\n⚖️ **RULES**: Scoring, faults, regulations, tournament rules\n🏟️ **FACILITIES**: Court booking, pricing, programs, services\n💪 **TRAINING**: Fitness, drills, nutrition, injury prevention\n📚 **KNOWLEDGE**: History, brands, comparisons, professional tips\n👥 **PROGRAMS**: Juniors, women's, recreational, competitive\n🌍 **TOURNAMENTS**: Major events, local competitions, preparation\n\n💬 **Hỏi tôi bất cứ điều gì về cầu lông!**\n\nVí dụ: \"Vợt tấn công tốt nhất?\", \"Cách smash mạnh?\", \"Luật đánh đôi?\", \"Giá đặt sân tối?\"\n\n🎯 **Bạn muốn tìm hiểu về chủ đề nào?**";
    }
    
    private function getProducts($question)
    {
        $q = strtolower($question);
        $products = [];
        
        // === PROFESSIONAL PLAYERS ===
        if ((strpos($q, "tuyển thủ") !== false || strpos($q, "chuyên nghiệp") !== false || strpos($q, "vđv") !== false || strpos($q, "pro") !== false) && (strpos($q, "tấn công") !== false || strpos($q, "chuyên công") !== false || strpos($q, "công") !== false)) {
            return [
                [
                    "id" => 1,
                    "name" => "Vợt Yonex Astrox 100ZZ",
                    "price" => 3200000,
                    "original_price" => 3500000,
                    "image" => "http://localhost:8000/uploads/products/yonex-astrox100zz.jpg",
                    "brand" => "Yonex",
                    "description" => "Ultimate attack racket với Namd technology..."
                ],
                [
                    "id" => 2,
                    "name" => "Vợt Yonex Astrox 99 Pro",
                    "price" => 2800000,
                    "original_price" => 3100000,
                    "image" => "http://localhost:8000/uploads/products/yonex-astrox99pro.jpg",
                    "brand" => "Yonex",
                    "description" => "Perfect balance attack-defense..."
                ],
                [
                    "id" => 3,
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
        if ((strpos($q, "tuyển thủ") !== false || strpos($q, "chuyên nghiệp") !== false) && (strpos($q, "đơn") !== false || strpos($q, "singles") !== false)) {
            return [
                [
                    "id" => 4,
                    "name" => "Vợt Yonex Nanoflare 800",
                    "price" => 2500000,
                    "original_price" => 2700000,
                    "image" => "http://localhost:8000/uploads/products/yonex-nanoflare800.jpg",
                    "brand" => "Yonex",
                    "description" => "Lightning speed cho singles domination..."
                ],
                [
                    "id" => 5,
                    "name" => "Vợt Yonex Astrox 88D Pro",
                    "price" => 2100000,
                    "original_price" => 2400000,
                    "image" => "http://localhost:8000/uploads/products/yonex-astrox88d.jpg",
                    "brand" => "Yonex",
                    "description" => "All-court weapon cho singles..."
                ]
            ];
        }
        
        // === PROFESSIONAL GENERAL ===
        if (strpos($q, "tuyển thủ") !== false || strpos($q, "chuyên nghiệp") !== false || strpos($q, "vđv") !== false || strpos($q, "pro") !== false) {
            return [
                [
                    "id" => 1,
                    "name" => "Vợt Yonex Astrox 100ZZ",
                    "price" => 3200000,
                    "original_price" => 3500000,
                    "image" => "http://localhost:8000/uploads/products/yonex-astrox100zz.jpg",
                    "brand" => "Yonex",
                    "description" => "Flagship premium cho professionals..."
                ],
                [
                    "id" => 6,
                    "name" => "Giày Yonex Power Cushion Aerus Z2",
                    "price" => 2550000,
                    "original_price" => 2800000,
                    "image" => "http://localhost:8000/uploads/products/yonex-aerusz2.jpg",
                    "brand" => "Yonex",
                    "description" => "Ultra-light professional shoes..."
                ]
            ];
        }
        
        // === TECHNIQUE QUERIES ===
        if (strpos($q, "smash") !== false || strpos($q, "đập") !== false || strpos($q, "cú đập") !== false) {
            return [
                [
                    "id" => 1,
                    "name" => "Vợt Yonex Astrox 100ZZ",
                    "price" => 3200000,
                    "original_price" => 3500000,
                    "image" => "http://localhost:8000/uploads/products/yonex-astrox100zz.jpg",
                    "brand" => "Yonex",
                    "description" => "Maximum smash power racket..."
                ],
                [
                    "id" => 7,
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
        if (strpos($q, "giày") !== false || strpos($q, "shoe") !== false) {
            if (strpos($q, "chuyên nghiệp") !== false || strpos($q, "pro") !== false) {
                return [
                    [
                        "id" => 6,
                        "name" => "Giày Yonex Power Cushion Aerus Z2",
                        "price" => 2550000,
                        "original_price" => 2800000,
                        "image" => "http://localhost:8000/uploads/products/yonex-aerusz2.jpg",
                        "brand" => "Yonex",
                        "description" => "Professional tournament shoes..."
                    ],
                    [
                        "id" => 8,
                        "name" => "Giày Yonex Power Cushion Infinity",
                        "price" => 1200000,
                        "original_price" => 1350000,
                        "image" => "http://localhost:8000/uploads/products/yonex-infinity.jpg",
                        "brand" => "Yonex",
                        "description" => "Ultimate comfort & support..."
                    ]
                ];
            } else {
                return [
                    [
                        "id" => 9,
                        "name" => "Giày Lining Ranger TD",
                        "price" => 450000,
                        "original_price" => 500000,
                        "image" => "http://localhost:8000/uploads/products/lining-ranger-td.jpg",
                        "brand" => "Lining",
                        "description" => "Affordable performance shoes..."
                    ],
                    [
                        "id" => 10,
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
        if ((strpos($q, "quả cầu") !== false || strpos($q, "shuttlecock") !== false || strpos($q, "shuttle") !== false) && strpos($q, "vợt") === false && strpos($q, "lịch sử") === false) {
            return [
                [
                    "id" => 11,
                    "name" => "Cầu Yonex AS-50 Tournament",
                    "price" => 220000,
                    "original_price" => 250000,
                    "image" => "http://localhost:8000/uploads/products/yonex-as50.jpg",
                    "brand" => "Yonex",
                    "description" => "Olympic standard shuttlecock..."
                ],
                [
                    "id" => 12,
                    "name" => "Cầu Victor Champion No.1",
                    "price" => 180000,
                    "original_price" => 200000,
                    "image" => "http://localhost:8000/uploads/products/victor-champion.jpg",
                    "brand" => "Victor",
                    "description" => "BWF approved tournament grade..."
                ]
            ];
        }
        
        // === RACKET CATEGORIES ===
        
        // Beginner rackets
        if (strpos($q, "vợt") !== false && (strpos($q, "mới") !== false || strpos($q, "người mới") !== false || strpos($q, "bắt đầu") !== false || strpos($q, "học") !== false)) {
            return [
                [
                    "id" => 13,
                    "name" => "Vợt Yonex Muscle Power 29 Light",
                    "price" => 85000,
                    "original_price" => 95000,
                    "image" => "http://localhost:8000/uploads/products/yonex-mp29l.jpg",
                    "brand" => "Yonex",
                    "description" => "Ultra-light beginner friendly..."
                ],
                [
                    "id" => 14,
                    "name" => "Vợt Yonex Carbonex 21",
                    "price" => 120000,
                    "original_price" => 135000,
                    "image" => "http://localhost:8000/uploads/products/yonex-carbonex21.jpg",
                    "brand" => "Yonex",
                    "description" => "Classic durable racket..."
                ]
            ];
        }
        
        // Attack rackets
        if (strpos($q, "vợt") !== false && (strpos($q, "tấn công") !== false || strpos($q, "chuyên công") !== false || strpos($q, "power") !== false || strpos($q, "mạnh") !== false)) {
            return [
                [
                    "id" => 1,
                    "name" => "Vợt Yonex Astrox 100ZZ",
                    "price" => 3200000,
                    "original_price" => 3500000,
                    "image" => "http://localhost:8000/uploads/products/yonex-astrox100zz.jpg",
                    "brand" => "Yonex",
                    "description" => "Ultimate power generation..."
                ],
                [
                    "id" => 3,
                    "name" => "Vợt Lining Aeronaut 9000C",
                    "price" => 2200000,
                    "original_price" => 2400000,
                    "image" => "http://localhost:8000/uploads/products/lining-an9000c.jpg",
                    "brand" => "Lining",
                    "description" => "High-performance attack specialist..."
                ]
            ];
        }
        
        // Singles rackets
        if (strpos($q, "vợt") !== false && (strpos($q, "đơn") !== false || strpos($q, "singles") !== false)) {
            return [
                [
                    "id" => 4,
                    "name" => "Vợt Yonex Nanoflare 800",
                    "price" => 2500000,
                    "original_price" => 2700000,
                    "image" => "http://localhost:8000/uploads/products/yonex-nanoflare800.jpg",
                    "brand" => "Yonex",
                    "description" => "Speed demon cho singles..."
                ],
                [
                    "id" => 15,
                    "name" => "Vợt Lining Bladex 900",
                    "price" => 1900000,
                    "original_price" => 2200000,
                    "image" => "http://localhost:8000/uploads/products/lining-bladex900.jpg",
                    "brand" => "Lining",
                    "description" => "Precision control cho singles..."
                ]
            ];
        }
        
        // Doubles rackets
        if (strpos($q, "vợt") !== false && (strpos($q, "đôi") !== false || strpos($q, "doubles") !== false || strpos($q, "cặp") !== false)) {
            return [
                [
                    "id" => 16,
                    "name" => "Vợt Yonex Astrox 88S Pro (Front)",
                    "price" => 2100000,
                    "original_price" => 2400000,
                    "image" => "http://localhost:8000/uploads/products/yonex-astrox88s.jpg",
                    "brand" => "Yonex",
                    "description" => "Perfect for doubles front court..."
                ],
                [
                    "id" => 17,
                    "name" => "Vợt Yonex Astrox 88D Pro (Back)",
                    "price" => 2100000,
                    "original_price" => 2400000,
                    "image" => "http://localhost:8000/uploads/products/yonex-astrox88d.jpg",
                    "brand" => "Yonex",
                    "description" => "Ideal for doubles back court power..."
                ]
            ];
        }
        
        // General racket queries
        if (strpos($q, "vợt") !== false) {
            return [
                [
                    "id" => 1,
                    "name" => "Vợt Yonex Astrox 100ZZ",
                    "price" => 3200000,
                    "original_price" => 3500000,
                    "image" => "http://localhost:8000/uploads/products/yonex-astrox100zz.jpg",
                    "brand" => "Yonex",
                    "description" => "Flagship premium racket..."
                ],
                [
                    "id" => 4,
                    "name" => "Vợt Yonex Nanoflare 800",
                    "price" => 2500000,
                    "original_price" => 2700000,
                    "image" => "http://localhost:8000/uploads/products/yonex-nanoflare800.jpg",
                    "brand" => "Yonex",
                    "description" => "Speed & agility specialist..."
                ],
                [
                    "id" => 13,
                    "name" => "Vợt Yonex Muscle Power 29L",
                    "price" => 85000,
                    "original_price" => 95000,
                    "image" => "http://localhost:8000/uploads/products/yonex-mp29l.jpg",
                    "brand" => "Yonex",
                    "description" => "Affordable entry-level option..."
                ]
            ];
        }
        
        // === PRICE QUERIES ===
        if (strpos($q, "đắt nhất") !== false || strpos($q, "dat nhat") !== false || strpos($q, "expensive") !== false || strpos($q, "cao cấp") !== false) {
            return [
                [
                    "id" => 1,
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
                    "description" => "Giày cao cấp siêu nhẹ..."
                ]
            ];
        }
        
        if (strpos($q, "rẻ nhất") !== false || strpos($q, "re nhat") !== false || strpos($q, "giá rẻ") !== false || strpos($q, "tiết kiệm") !== false) {
            return [
                [
                    "id" => 13,
                    "name" => "Vợt Yonex Muscle Power 29L",
                    "price" => 85000,
                    "original_price" => 95000,
                    "image" => "http://localhost:8000/uploads/products/yonex-mp29l.jpg",
                    "brand" => "Yonex",
                    "description" => "Vợt phổ thông chất lượng tốt..."
                ],
                [
                    "id" => 9,
                    "name" => "Giày Lining Ranger TD",
                    "price" => 450000,
                    "original_price" => 500000,
                    "image" => "http://localhost:8000/uploads/products/lining-ranger-td.jpg",
                    "brand" => "Lining",
                    "description" => "Affordable performance shoes..."
                ]
            ];
        }
        
        // === DEFAULT PRODUCTS ===
        return [
            [
                "id" => 1,
                "name" => "Vợt Yonex Astrox 100ZZ",
                "price" => 3200000,
                "original_price" => 3500000,
                "image" => "http://localhost:8000/uploads/products/yonex-astrox100zz.jpg",
                "brand" => "Yonex",
                "description" => "Vợt tấn công hàng đầu..."
            ],
            [
                "id" => 6,
                "name" => "Giày Yonex Power Cushion Aerus Z",
                "price" => 850000,
                "original_price" => 950000,
                "image" => "http://localhost:8000/uploads/products/yonex-aerus-z.jpg",
                "brand" => "Yonex",
                "description" => "Giày siêu nhẹ chuyên nghiệp..."
            ]
        ];
    }
}