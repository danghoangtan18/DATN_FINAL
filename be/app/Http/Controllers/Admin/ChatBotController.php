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
        
        // ===== 18. MUA BÁN & ĐƠN HÀNG =====
        if (strpos($q, "mua") !== false || strpos($q, "buy") !== false || strpos($q, "order") !== false || strpos($q, "đặt hàng") !== false) {
            return "🛒 **HƯỚNG DẪN MUA HÀNG TẠI VICNEX**:\n\n📱 **3 Cách đặt hàng dễ dàng**:\n🌐 **Online**: Website → Chọn sản phẩm → Thêm giỏ hàng → Thanh toán\n📞 **Hotline**: 1800-VICNEX (24/7 support)\n🏪 **Tại cửa hàng**: Trực tiếp tại showroom\n\n✅ **Quy trình đơn giản**:\n1️⃣ Chọn sản phẩm\n2️⃣ Thêm vào giỏ hàng\n3️⃣ Điền thông tin giao hàng\n4️⃣ Chọn phương thức thanh toán\n5️⃣ Xác nhận đơn hàng\n6️⃣ Theo dõi vận chuyển\n\n📦 **Miễn phí ship từ 500k**\n🎁 **Tặng kèm grip tape cho vợt**\n\nBạn muốn đặt sản phẩm nào?";
        }
        
        if (strpos($q, "trả góp") !== false || strpos($q, "installment") !== false || strpos($q, "phân kỳ") !== false || strpos($q, "chia nhỏ") !== false) {
            return "💳 **TRẢ GÓP LINH HOẠT VICNEX**:\n\n💰 **Các gói trả góp**:\n🏦 **0% lãi suất** (3-6 tháng):\n• Đơn hàng từ 2 triệu\n• Thẻ tín dụng partner banks\n• Phê duyệt tức thì online\n\n📱 **Trả góp qua ví điện tử**:\n🔸 **MoMo**: 3-12 tháng, từ 500k\n🔸 **ZaloPay**: 3-9 tháng, từ 1tr\n🔸 **ViettelPay**: 3-6 tháng, từ 800k\n\n🏪 **Trả góp tại cửa hàng**:\n• Đặt cọc 30% → Nhận hàng\n• Số tiền còn lại chia đều\n• Không lãi suất, không phí\n• Thời hạn linh hoạt\n\n📋 **Thủ tục đơn giản**:\n✅ CMND/CCCD + số điện thoại\n✅ Xác minh thu nhập cơ bản\n✅ Phê duyệt trong 5 phút\n\nBạn muốn trả góp sản phẩm nào?";
        }
        
        if (strpos($q, "thanh toán") !== false || strpos($q, "payment") !== false || strpos($q, "trả tiền") !== false) {
            return "💳 **PHƯƠNG THỨC THANH TOÁN**:\n\n🏦 **Banking & Cards**:\n💳 Visa/Mastercard/JCB - Secure 3D\n🏧 Internet Banking - All major banks\n📱 E-wallets: MoMo, ZaloPay, ViettelPay\n🪙 QR Code payment - Fast & secure\n\n💰 **Cash Options**:\n🏪 Thanh toán tại cửa hàng\n🚚 COD - Ship tận nơi (phí 25k)\n\n🔒 **Security Features**:\n✅ SSL encryption\n✅ PCI DSS compliant\n✅ Fraud protection\n✅ 24/7 monitoring\n\n🎯 **Ưu đãi thanh toán**:\n💳 Banking: Giảm 2%\n📱 E-wallet: Giảm 1%\n\nPhương thức nào bạn thích?";
        }
        
        if (strpos($q, "giao hàng") !== false || strpos($q, "ship") !== false || strpos($q, "vận chuyển") !== false || strpos($q, "delivery") !== false) {
            return "🚚 **DỊCH VỤ GIAO HÀNG VICNEX**:\n\n⚡ **Tốc độ giao hàng**:\n🏍️ **Express**: 2-4 giờ (nội thành, phí 50k)\n📦 **Standard**: 1-2 ngày (toàn quốc, phí 25k)\n🚛 **Economy**: 2-5 ngày (vùng xa, phí 35k)\n\n🎁 **Miễn phí ship**:\n✅ Đơn hàng từ 500k\n✅ Thành viên VIP\n✅ Khuyến mãi đặc biệt\n\n📍 **Coverage**: Toàn quốc 63 tỉnh thành\n📱 **Tracking**: SMS + Email updates\n🔄 **Flexible**: Đổi lịch giao hàng\n\n📦 **Đóng gói cẩn thận**:\n• Bubble wrap cho vợt\n• Hộp carton cứng\n• Chống shock, chống ẩm\n\nĐịa chỉ nào bạn cần giao?";
        }
        
        if (strpos($q, "bảo hành") !== false || strpos($q, "warranty") !== false || strpos($q, "guarantee") !== false) {
            return "🛡️ **CHÍNH SÁCH BẢO HÀNH VICNEX**:\n\n⏰ **Thời gian bảo hành**:\n🏸 **Vợt**: 12 tháng (lỗi kỹ thuật)\n👟 **Giày**: 6 tháng (đế, upper)\n👕 **Quần áo**: 3 tháng (phai màu, bong tróc)\n⚙️ **Phụ kiện**: 6 tháng (theo nhà sản xuất)\n\n✅ **Bảo hành gì**:\n• Lỗi sản xuất • Chất liệu kém • Hư hỏng không do người dùng\n\n❌ **Không bảo hành**:\n• Va đập mạnh • Sử dụng sai cách • Hết hạn\n\n🔧 **Quy trình bảo hành**:\n1️⃣ Mang sản phẩm + phiếu bảo hành\n2️⃣ Kiểm tra tình trạng\n3️⃣ Sửa chữa/thay thế\n4️⃣ Giao hàng (miễn phí)\n\nBạn cần bảo hành sản phẩm nào?";
        }
        
        if (strpos($q, "đổi trả") !== false || strpos($q, "return") !== false || strpos($q, "hoàn tiền") !== false || strpos($q, "refund") !== false) {
            return "🔄 **CHÍNH SÁCH ĐỔI TRẢ**:\n\n⏰ **Thời gian đổi trả**:\n📅 **15 ngày** với sản phẩm chưa sử dụng\n🏷️ **7 ngày** với sản phẩm lỗi từ nhà sản xuất\n\n✅ **Điều kiện đổi trả**:\n• Sản phẩm nguyên vẹn, chưa qua sử dụng\n• Còn nguyên tem, mác, nhãn hiệu\n• Có hóa đơn mua hàng\n• Đóng gói đầy đủ như ban đầu\n\n💰 **Chi phí đổi trả**:\n🆓 **Miễn phí**: Lỗi từ nhà sản xuất\n💳 **Khách trả**: Đổi ý, không vừa ý (50k ship)\n\n🔄 **Quy trình**:\n1️⃣ Liên hệ hotline trong 24h\n2️⃣ Đóng gói sản phẩm\n3️⃣ Ship về Vicnex\n4️⃣ Kiểm tra & xử lý\n5️⃣ Hoàn tiền/đổi mới\n\nSản phẩm nào bạn muốn đổi trả?";
        }
        
        if (strpos($q, "khuyến mãi") !== false || strpos($q, "promotion") !== false || strpos($q, "giảm giá") !== false || strpos($q, "sale") !== false || strpos($q, "ưu đãi") !== false) {
            return "🎁 **KHUYẾN MÃI HOT VICNEX**:\n\n🔥 **Flash Sale hàng ngày**:\n⏰ **10:00**: Giảm 15% vợt Yonex\n⏰ **14:00**: Giảm 20% giày cầu lông\n⏰ **20:00**: Buy 1 Get 1 phụ kiện\n\n💎 **Ưu đãi thành viên**:\n🥉 **Bronze**: 5% mọi đơn hàng\n🥈 **Silver**: 10% + free ship\n🥇 **Gold**: 15% + priority support\n💎 **Diamond**: 20% + exclusive access\n\n🎯 **Combo deals**:\n🏸 Vợt + Bao = Giảm 25%\n👟 Giày + Tất = Giảm 30%\n👕 Bộ đồ đấu = Giảm 35%\n\n📅 **Sự kiện đặc biệt**:\n🎂 Sinh nhật Vicnex: 50% off\n🛍️ 11.11, 12.12: Super sale\n🎊 Tết Nguyên đán: Lucky draw\n\nMã giảm giá nào bạn quan tâm?";
        }
        
        if (strpos($q, "thành viên") !== false || strpos($q, "membership") !== false || strpos($q, "vip") !== false || strpos($q, "tích điểm") !== false) {
            return "👑 **CHƯƠNG TRÌNH THÀNH VIÊN VICNEX**:\n\n🎯 **4 Hạng thành viên**:\n🥉 **Bronze** (0-999k): 5% discount\n🥈 **Silver** (1-4.9tr): 10% + free ship\n🥇 **Gold** (5-19.9tr): 15% + priority\n💎 **Diamond** (20tr+): 20% + VIP service\n\n💰 **Tích điểm thông minh**:\n• 1k chi tiêu = 1 điểm\n• 100 điểm = 10k voucher\n• Điểm không hết hạn\n• Tặng điểm sinh nhật\n\n🎁 **Quyền lợi độc quyền**:\n✅ Ưu tiên đặt hàng limited items\n✅ Tham gia sự kiện VIP\n✅ Tư vấn cá nhân hóa\n✅ Bảo hành ưu tiên\n✅ Đổi trả linh hoạt\n\n📱 **Đăng ký ngay**: App Vicnex → Tạo tài khoản → Tích điểm\n\nBạn muốn đăng ký thành viên?";
        }
        
        if (strpos($q, "so sánh") !== false || strpos($q, "compare") !== false || strpos($q, "khác nhau") !== false || strpos($q, "vs") !== false) {
            return "⚖️ **SO SÁNH SẢN PHẨM CHUYÊN NGHIỆP**:\n\n🔍 **Tiêu chí so sánh**:\n⚡ **Performance**: Power, speed, control, feel\n💰 **Price**: Value for money, cost-effectiveness\n🏷️ **Brand**: Reputation, technology, endorsements\n🎯 **Suitability**: Beginner, intermediate, advanced\n🛠️ **Build**: Materials, durability, craftsmanship\n\n📊 **Popular comparisons**:\n🆚 **Astrox 88D vs 88S**: Attack vs Speed\n🆚 **Yonex vs Lining**: Premium vs Value\n🆚 **Aerus vs Power Cushion**: Light vs Stable\n🆚 **BG65 vs BG80**: Durability vs Power\n\n💡 **Tư vấn cá nhân hóa**:\n• Phong cách chơi của bạn\n• Ngân sách mong muốn\n• Mục tiêu cải thiện\n• Kinh nghiệm hiện tại\n\nBạn muốn so sánh sản phẩm nào?";
        }
        
        if (strpos($q, "tư vấn") !== false || strpos($q, "consultation") !== false || strpos($q, "advice") !== false || strpos($q, "nên chọn") !== false) {
            return "🎯 **TỦ VẤN CÁ NHÂN HÓA VICNEX**:\n\n📝 **Quy trình tư vấn chuyên nghiệp**:\n1️⃣ **Đánh giá**: Trình độ, phong cách, mục tiêu\n2️⃣ **Phân tích**: Điểm mạnh, điểm cần cải thiện\n3️⃣ **Đề xuất**: Top 3 lựa chọn phù hợp\n4️⃣ **Giải thích**: Tại sao phù hợp với bạn\n5️⃣ **Test**: Thử trước khi mua (có thể)\n\n👨‍🏫 **Đội ngũ chuyên gia**:\n🏆 **HLV chuyên nghiệp**: 10+ năm kinh nghiệm\n🏸 **Cựu VĐV**: Hiểu tâm lý người chơi\n🔬 **Kỹ thuật viên**: Chuyên sâu về thiết bị\n💼 **Sales consultant**: Tư vấn ngân sách\n\n📱 **Các hình thức tư vấn**:\n🏪 **Tại cửa hàng**: Trực tiếp, test sản phẩm\n📞 **Hotline**: Nhanh chóng, tiện lợi\n💬 **Chat online**: 24/7 support\n📧 **Email**: Chi tiết, có hình ảnh\n\nBạn muốn được tư vấn về vấn đề gì?";
        }
        
        if (strpos($q, "chất lượng") !== false || strpos($q, "quality") !== false || strpos($q, "authentic") !== false || strpos($q, "chính hãng") !== false) {
            return "✅ **CAM KẾT CHẤT LƯỢNG VICNEX**:\n\n🏷️ **100% hàng chính hãng**:\n• Nhập khẩu trực tiếp từ nhà sản xuất\n• Có CO, CQ, stamp chính thức\n• Bảo hành toàn cầu\n• Kiểm tra chất lượng 3 lần\n\n🔍 **Cách nhận biết hàng thật**:\n📱 **QR code**: Scan kiểm tra authenticity\n🏷️ **Hologram**: Anti-counterfeit sticker\n📄 **Certificate**: Giấy chứng nhận chính hãng\n🔢 **Serial number**: Unique product ID\n\n🛡️ **Chính sách chất lượng**:\n❌ **Fake = Hoàn tiền gấp 10 lần**\n✅ **Đổi mới nếu lỗi nhà sản xuất**\n🔄 **Test thử 7 ngày đầu**\n📞 **Hotline quality control 24/7**\n\n🏆 **Chứng nhận quốc tế**:\n• ISO 9001:2015\n• Authorized dealer official\n• BWF approved equipment\n\nBạn có thắc mắc về chất lượng sản phẩm nào?";
        }
        
        if (strpos($q, "size") !== false || strpos($q, "kích thước") !== false || strpos($q, "grip") !== false || strpos($q, "số") !== false) {
            return "📏 **HƯỚNG DẪN CHỌN SIZE**:\n\n🏸 **Grip size vợt**:\n• **G4** (XS): 82mm - Tay nhỏ, nữ, trẻ em\n• **G5** (S): 85mm - Standard, phổ biến nhất\n• **G6** (M): 88mm - Tay to, nam giới\n• **Grip tape**: +2mm thickness\n\n👟 **Size giày cầu lông**:\n📐 **Cách đo**: Đứng thẳng, đo từ gót → mũi chân dài nhất\n📊 **Size chart**:\n• 38: 24.0cm • 39: 24.5cm • 40: 25.0cm\n• 41: 25.5cm • 42: 26.0cm • 43: 26.5cm\n• 44: 27.0cm • 45: 27.5cm\n\n👕 **Quần áo**:\n📏 **Size chart Asia**:\n• S: 50-55kg • M: 55-65kg\n• L: 65-75kg • XL: 75-85kg • XXL: 85-95kg\n\n💡 **Mẹo chọn size**: Nên chọn vừa vặn, không quá rộng hay chật\n\nBạn cần tư vấn size sản phẩm nào?";
        }
        
        if (strpos($q, "mới nhất") !== false || strpos($q, "new") !== false || strpos($q, "latest") !== false || strpos($q, "2024") !== false || strpos($q, "2025") !== false) {
            return "🆕 **SẢN PHẨM MỚI NHẤT 2025**:\n\n🏸 **Rackets mới**:\n🔥 **Yonex Astrox 100ZX**: Game-changer technology\n⚡ **Lining Aeronaut 9000D**: Dual power zones\n🎯 **Victor Jetspeed S15**: Ultra-responsive\n\n👟 **Shoes collection**:\n👑 **Yonex Power Cushion Infinity**: Max comfort\n⚡ **Lining Ranger TD-5**: Speed demon\n🛡️ **Victor P9500**: Ultimate stability\n\n👕 **Apparel trends**:\n🌟 **Team Malaysia 2025**: Official replica\n🎨 **Limited Edition**: Artist collaboration\n🏆 **Tournament gear**: Pro-level quality\n\n💎 **Exclusive features**:\n• Namd+ technology (Yonex)\n• Sonic Boom tech (Lining)\n• Energy Max 4.0 (Victor)\n\n📅 **Launch calendar**:\n• Q1 2025: Spring collection\n• Q2 2025: Tournament specials\n• Q3 2025: Olympic preparation\n\nSản phẩm nào bạn quan tâm nhất?";
        }
        
        if (strpos($q, "pre order") !== false || strpos($q, "đặt trước") !== false || strpos($q, "preorder") !== false) {
            return "📅 **PRE-ORDER VICNEX**:\n\n🎯 **Sản phẩm đặt trước**:\n🏸 **Limited editions**: Vợt phiên bản giới hạn\n👟 **New launches**: Giày mới ra mắt\n👕 **Team jerseys**: Áo đội tuyển chính thức\n🏆 **Tournament gear**: Thiết bị thi đấu đặc biệt\n\n💰 **Ưu đãi pre-order**:\n🎁 **Early bird**: Giảm 15-25%\n📦 **Free shipping**: Miễn phí vận chuyển\n🎊 **Exclusive gifts**: Quà tặng độc quyền\n👑 **Priority access**: Ưu tiên nhận hàng\n\n⏰ **Timeline**:\n📝 **Đặt cọc**: 30-50% giá trị\n⏳ **Lead time**: 2-8 tuần\n📦 **Delivery**: Ngay khi hàng về\n💳 **Thanh toán**: Linh hoạt, trả góp\n\n🔄 **Chính sách**:\n✅ **Đổi trả**: 30 ngày\n🛡️ **Bảo hành**: Từ ngày nhận hàng\n❌ **Hủy đơn**: Hoàn cọc 100% nếu delay\n\nBạn muốn pre-order sản phẩm nào?";
        }
        
        if (strpos($q, "combo") !== false || strpos($q, "set") !== false || strpos($q, "bộ") !== false || strpos($q, "package") !== false) {
            return "🎁 **COMBO DEALS VICNEX**:\n\n🏸 **Complete Player Sets**:\n👤 **Beginner Combo** (500k-1tr):\n• Vợt entry level + Bao vợt + Grip\n• Giày cơ bản + Tất cầu lông\n• Áo basic + Quần shorts\n\n🎯 **Intermediate Package** (1.5-3tr):\n• Vợt tầm trung + Professional bag\n• Giày chuyên nghiệp + Premium socks\n• Bộ đồ thi đấu + Towel\n\n🏆 **Professional Set** (3-6tr):\n• Top-tier racket + Premium case\n• Pro shoes + Compression wear\n• Tournament outfit + Accessories\n\n💎 **Ultimate Champion** (6tr+):\n• Flagship rackets (2 cây) + Luxury bag\n• Top shoes + Complete apparel\n• All accessories + Personal service\n\n🎯 **Savings**: Tiết kiệm 20-40% so với mua lẻ\n🎁 **Freebies**: Tặng kèm nhiều phụ kiện\n\nCombo nào phù hợp với bạn?";
        }
        
        if (strpos($q, "stock") !== false || strpos($q, "có hàng") !== false || strpos($q, "còn hàng") !== false || strpos($q, "availability") !== false) {
            return "📦 **TÌNH TRẠNG KHO HÀNG**:\n\n✅ **Real-time inventory**:\n🟢 **Có sẵn**: Giao ngay trong ngày\n🟡 **Sắp hết**: 1-5 sản phẩm cuối\n🔴 **Hết hàng**: Pre-order hoặc chờ về\n⚪ **Discontinued**: Ngừng sản xuất\n\n📱 **Cách kiểm tra**:\n🌐 **Website**: Auto-update mỗi giờ\n📞 **Hotline**: Check realtime\n🏪 **Tại cửa hàng**: Xem trực tiếp\n💬 **Chat**: Bot tự động thông báo\n\n⚡ **Stock alert service**:\n📧 **Email**: Thông báo khi có hàng\n📱 **SMS**: Alert ngay lập tức\n🔔 **App push**: Notification instant\n👥 **Priority**: Thành viên VIP ưu tiên\n\n📊 **Popular items status**:\n🏸 **Astrox 100ZZ**: Limited stock\n👟 **Power Cushion**: Full stock\n👕 **Team Malaysia**: Pre-order\n\nSản phẩm nào bạn muốn kiểm tra stock?";
        }
        
        if (strpos($q, "review") !== false || strpos($q, "đánh giá") !== false || strpos($q, "feedback") !== false || strpos($q, "rating") !== false) {
            return "⭐ **ĐÁNH GIÁ & FEEDBACK**:\n\n📊 **Hệ thống rating 5 sao**:\n⭐⭐⭐⭐⭐ **Xuất sắc** (90%+)\n⭐⭐⭐⭐ **Tốt** (80-89%)\n⭐⭐⭐ **Khá** (70-79%)\n⭐⭐ **Trung bình** (60-69%)\n⭐ **Kém** (<60%)\n\n🏆 **Top rated products**:\n🥇 **Yonex Astrox 100ZZ**: 4.9/5 (2847 reviews)\n🥈 **Power Cushion Infinity**: 4.8/5 (1923 reviews)\n🥉 **Lining Aeronaut 9000C**: 4.7/5 (1456 reviews)\n\n💬 **Chi tiết đánh giá**:\n✅ **Verified purchase**: Chỉ khách mua hàng\n📝 **Detailed reviews**: Ưu/nhược điểm\n📸 **Photo reviews**: Hình ảnh thực tế\n🎥 **Video reviews**: Unboxing, testing\n\n🎁 **Review rewards**:\n💰 **Cashback**: 50k cho review chi tiết\n🎊 **Lucky draw**: Review ảnh/video\n👑 **VIP points**: Tích điểm thưởng\n\nBạn muốn xem review sản phẩm nào?";
        }
        
        if (strpos($q, "support") !== false || strpos($q, "hỗ trợ") !== false || strpos($q, "help") !== false || strpos($q, "giúp đỡ") !== false) {
            return "🤝 **HỖ TRỢ KHÁCH HÀNG 24/7**:\n\n📞 **Hotline**: 1800-VICNEX\n⏰ **24/7 support**: Luôn sẵn sàng phục vụ\n🌐 **Multi-channel**: Phone, chat, email, social\n\n👥 **Đội ngũ support**:\n🎯 **Technical**: Chuyên gia kỹ thuật\n💼 **Sales**: Tư vấn bán hàng\n🔧 **After-sales**: Hỗ trợ sau mua\n🌍 **Language**: Vietnamese, English\n\n💬 **Live chat features**:\n🤖 **AI Bot**: Trả lời tức thì\n👨‍💼 **Human agent**: Hỗ trợ chuyên sâu\n📱 **Mobile app**: Chat mọi lúc mọi nơi\n📋 **Ticket system**: Theo dõi vấn đề\n\n🎯 **Chúng tôi hỗ trợ**:\n• Tư vấn sản phẩm • Hướng dẫn sử dụng\n• Xử lý khiếu nại • Bảo hành sửa chữa\n• Hỗ trợ thanh toán • Tracking đơn hàng\n\n⚡ **Response time**: < 2 phút\n\nBạn cần hỗ trợ vấn đề gì?";
        }
        
        // ===== 19. OUTLET & CLEARANCE =====
        if (strpos($q, "outlet") !== false || strpos($q, "clearance") !== false || strpos($q, "xả hàng") !== false || strpos($q, "liquidation") !== false) {
            return "🔥 **OUTLET & CLEARANCE SALE**:\n\n💥 **Xả kho đặc biệt**:\n📅 **Monthly clearance**: Cuối tháng\n🎯 **Overstock**: Hàng tồn kho\n📦 **Display items**: Hàng trưng bày\n🎨 **Discontinued**: Ngừng sản xuất\n\n💰 **Mức giảm khủng**:\n⚡ **50-70% OFF**: Vợt cũ\n👟 **40-60% OFF**: Giày past season\n👕 **30-50% OFF**: Apparel outlet\n🎒 **20-40% OFF**: Accessories\n\n✅ **Cam kết outlet**:\n🏷️ **Chính hãng 100%**: Authentic guarantee\n🛡️ **Bảo hành đầy đủ**: Như hàng mới\n🔄 **Đổi trả bình thường**: 7-15 ngày\n📦 **Chất lượng tốt**: Kiểm tra kỹ\n\n🎁 **Flash outlet deals**:\n⏰ **Every Friday 8PM**: Flash sale 2h\n📱 **App exclusive**: Member only deals\n🎪 **Warehouse sale**: Tháng 3,6,9,12\n\nBạn tìm outlet sản phẩm nào?";
        }
        
        if (strpos($q, "gift") !== false || strpos($q, "quà tặng") !== false || strpos($q, "tặng") !== false || strpos($q, "present") !== false) {
            return "🎁 **DỊCH VỤ QUÀ TẶNG VICNEX**:\n\n🎊 **Gift packages available**:\n🏸 **Racket gift set**: Vợt + Bao + Card\n👟 **Shoes luxury box**: Premium packaging\n🎒 **Accessory bundle**: Combo phụ kiện\n💳 **Gift vouchers**: Flexible amount\n\n🎀 **Gift services**:\n📦 **Gift wrapping**: Free beautiful wrap\n💌 **Personal message**: Handwritten card\n📅 **Scheduled delivery**: Đúng ngày sinh nhật\n🎪 **Surprise delivery**: Bất ngờ người nhận\n\n💡 **Gift ideas by occasion**:\n🎂 **Sinh nhật**: Racket + personalization\n🎓 **Tốt nghiệp**: Professional equipment\n🏆 **Thành tích**: Premium reward\n💍 **Kỷ niệm**: Custom engraving\n\n💳 **Gift voucher benefits**:\n⏰ **Validity**: 12 tháng\n🛒 **Usage**: Mọi sản phẩm\n🎯 **Flexible**: Số tiền tùy chọn\n📱 **Digital**: Email delivery\n\nBạn cần tư vấn quà tặng cho ai?";
        }
        
        if (strpos($q, "corporate") !== false || strpos($q, "doanh nghiệp") !== false || strpos($q, "bulk") !== false || strpos($q, "số lượng lớn") !== false) {
            return "🏢 **GIẢI PHÁP DOANH NGHIỆP**:\n\n🎯 **Corporate solutions**:\n🏢 **Company tournaments**: Thiết bị thi đấu\n🎁 **Employee gifts**: Quà tặng nhân viên\n🏆 **Awards & prizes**: Giải thưởng\n👕 **Custom uniforms**: Đồng phục có logo\n\n💼 **Bulk order benefits**:\n💰 **Volume discount**: 10-30% off\n🎨 **Customization**: Logo, colors, design\n📦 **Flexible delivery**: Schedule phù hợp\n💳 **Payment terms**: Credit options\n\n📊 **Minimum quantities**:\n🏸 **Rackets**: 20+ pieces\n👟 **Shoes**: 50+ pairs\n👕 **Apparel**: 100+ items\n🎒 **Accessories**: 200+ units\n\n🎨 **Customization services**:\n🖨️ **Screen printing**: Logo, text\n🧵 **Embroidery**: Premium finish\n🎨 **Custom colors**: Brand matching\n📦 **Packaging**: Company branding\n\n📞 **B2B contact**: corporate@vicnex.vn\n\nDoanh nghiệp bạn cần gì?";
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
        
        // === SALES QUERIES PRODUCT RECOMMENDATIONS ===
        
        // Purchase/Order related
        if (strpos($q, "mua") !== false || strpos($q, "order") !== false || strpos($q, "đặt hàng") !== false) {
            return [
                [
                    "id" => 1,
                    "name" => "Vợt Yonex Astrox 100ZZ - Best Seller",
                    "price" => 3200000,
                    "original_price" => 3500000,
                    "image" => "http://localhost:8000/uploads/products/yonex-astrox100zz.jpg",
                    "brand" => "Yonex",
                    "description" => "Top seller, được ưa chuộng nhất..."
                ],
                [
                    "id" => 6,
                    "name" => "Giày Yonex Power Cushion Infinity",
                    "price" => 1300000,
                    "original_price" => 1450000,
                    "image" => "http://localhost:8000/uploads/products/yonex-infinity.jpg",
                    "brand" => "Yonex",
                    "description" => "Comfort tối đa, bán chạy nhất..."
                ]
            ];
        }
        
        // Promotion/Sale related
        if (strpos($q, "khuyến mãi") !== false || strpos($q, "sale") !== false || strpos($q, "giảm giá") !== false) {
            return [
                [
                    "id" => 18,
                    "name" => "Vợt Lining Aeronaut 8000C - 30% OFF",
                    "price" => 1400000,
                    "original_price" => 2000000,
                    "image" => "http://localhost:8000/uploads/products/lining-aeronaut8000c.jpg",
                    "brand" => "Lining",
                    "description" => "Flash sale 30% - Limited time..."
                ],
                [
                    "id" => 19,
                    "name" => "Combo Beginner Set - 40% OFF",
                    "price" => 600000,
                    "original_price" => 1000000,
                    "image" => "http://localhost:8000/uploads/products/beginner-combo.jpg",
                    "brand" => "Vicnex",
                    "description" => "Combo người mới bắt đầu siêu tiết kiệm..."
                ]
            ];
        }
        
        // Quality/Authentic related
        if (strpos($q, "chính hãng") !== false || strpos($q, "authentic") !== false || strpos($q, "chất lượng") !== false) {
            return [
                [
                    "id" => 1,
                    "name" => "Vợt Yonex Astrox 100ZZ - Chính hãng",
                    "price" => 3200000,
                    "original_price" => 3500000,
                    "image" => "http://localhost:8000/uploads/products/yonex-astrox100zz.jpg",
                    "brand" => "Yonex",
                    "description" => "100% chính hãng, có QR code kiểm tra..."
                ],
                [
                    "id" => 20,
                    "name" => "Giày Victor Professional - Authentic",
                    "price" => 1800000,
                    "original_price" => 2000000,
                    "image" => "http://localhost:8000/uploads/products/victor-pro.jpg",
                    "brand" => "Victor",
                    "description" => "Authentic guarantee, bảo hành toàn cầu..."
                ]
            ];
        }
        
        // New products
        if (strpos($q, "mới nhất") !== false || strpos($q, "new") !== false || strpos($q, "2025") !== false) {
            return [
                [
                    "id" => 21,
                    "name" => "Vợt Yonex Astrox 100ZX - New 2025",
                    "price" => 3800000,
                    "original_price" => 4200000,
                    "image" => "http://localhost:8000/uploads/products/yonex-astrox100zx.jpg",
                    "brand" => "Yonex",
                    "description" => "Latest 2025 model với Namd+ technology..."
                ],
                [
                    "id" => 22,
                    "name" => "Giày Lining Ranger TD-5 - New Launch",
                    "price" => 2200000,
                    "original_price" => 2500000,
                    "image" => "http://localhost:8000/uploads/products/lining-td5.jpg",
                    "brand" => "Lining",
                    "description" => "Revolutionary 2025 design, ultra-responsive..."
                ]
            ];
        }
        
        // Combo/Set related
        if (strpos($q, "combo") !== false || strpos($q, "set") !== false || strpos($q, "bộ") !== false) {
            return [
                [
                    "id" => 23,
                    "name" => "Beginner Complete Set",
                    "price" => 800000,
                    "original_price" => 1200000,
                    "image" => "http://localhost:8000/uploads/products/beginner-set.jpg",
                    "brand" => "Vicnex",
                    "description" => "Vợt + Giày + Áo + Bao vợt + Phụ kiện..."
                ],
                [
                    "id" => 24,
                    "name" => "Professional Player Package",
                    "price" => 4500000,
                    "original_price" => 6000000,
                    "image" => "http://localhost:8000/uploads/products/pro-package.jpg",
                    "brand" => "Vicnex",
                    "description" => "Premium racket + Pro shoes + Tournament outfit..."
                ]
            ];
        }
        
        // Gift related
        if (strpos($q, "quà tặng") !== false || strpos($q, "gift") !== false || strpos($q, "tặng") !== false) {
            return [
                [
                    "id" => 25,
                    "name" => "Gift Set Premium - Perfect Present",
                    "price" => 2500000,
                    "original_price" => 3000000,
                    "image" => "http://localhost:8000/uploads/products/gift-set-premium.jpg",
                    "brand" => "Vicnex",
                    "description" => "Beautiful gift packaging + premium products..."
                ],
                [
                    "id" => 26,
                    "name" => "Personalized Racket - Custom Gift",
                    "price" => 3500000,
                    "original_price" => 4000000,
                    "image" => "http://localhost:8000/uploads/products/custom-racket.jpg",
                    "brand" => "Yonex",
                    "description" => "Engraved name + custom colors, unique gift..."
                ]
            ];
        }
        
        // Budget/Cheap related  
        if (strpos($q, "rẻ nhất") !== false || strpos($q, "giá rẻ") !== false || strpos($q, "budget") !== false) {
            return [
                [
                    "id" => 13,
                    "name" => "Vợt Yonex Muscle Power 29L - Budget",
                    "price" => 85000,
                    "original_price" => 95000,
                    "image" => "http://localhost:8000/uploads/products/muscle-power-29l.jpg",
                    "brand" => "Yonex", 
                    "description" => "Entry level tốt nhất trong tầm giá..."
                ],
                [
                    "id" => 27,
                    "name" => "Giày Lining Basic - Affordable",
                    "price" => 300000,
                    "original_price" => 350000,
                    "image" => "http://localhost:8000/uploads/products/lining-basic.jpg",
                    "brand" => "Lining",
                    "description" => "Chất lượng tốt, giá cả phải chăng..."
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