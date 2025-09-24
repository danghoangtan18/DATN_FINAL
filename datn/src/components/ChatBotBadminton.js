import React, { useState } from "react";

const robotImg = "https://cdn-icons-png.flaticon.com/512/4712/4712027.png";

function ChatBotBadminton() {
  const [open, setOpen] = useState(false);
  const [messages, setMessages] = useState([
    {
      from: "bot",
      text: "Xin chào! Tôi là trợ lý bán hàng chuyên nghiệp của Vicnex 🏸\n\nTôi có thể giúp bạn:\n• Tư vấn vợt theo trình độ & phong cách\n• Chọn giày, trang phục phù hợp\n• Đặt sân cầu lông\n• Tư vấn ngân sách\n\nBạn cần hỗ trợ gì hôm nay? 😊"
    }
  ]);
  const [input, setInput] = useState("");
  const [loading, setLoading] = useState(false);
  const [sessionId, setSessionId] = useState(() => {
    // Tạo session ID duy nhất cho mỗi user
    return localStorage.getItem('chatbot_session') || 
           'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
  });

  // Lưu session vào localStorage
  React.useEffect(() => {
    localStorage.setItem('chatbot_session', sessionId);
  }, [sessionId]);

  // Gửi câu hỏi lên backend và nhận câu trả lời
  const sendMessage = async () => {
    if (!input.trim()) return;
    setMessages(prev => [...prev, { from: "user", text: input }]);
    setLoading(true);

    try {
      const res = await fetch("http://localhost:8000/api/chatbot/badminton", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ 
          question: input,
          session_id: sessionId 
        })
      });
      const data = await res.json();
      
      // Cập nhật session ID nếu server trả về
      if (data.session_id) {
        setSessionId(data.session_id);
      }
      
      setMessages(prev => [
        ...prev,
        { from: "bot", text: data.answer, products: data.products }
      ]);
    } catch {
      setMessages(prev => [
        ...prev,
        { from: "bot", text: "Xin lỗi, tôi đang gặp trục trặc kỹ thuật. Bạn có thể thử lại sau một chút được không? 🙏" }
      ]);
    }
    setInput("");
    setLoading(false);
  };

  return (
    <div>
      {/* Nút mở chatbox và lời chào */}
      {!open && (
        <div style={{ position: "fixed", bottom: 32, right: 32, zIndex: 1000 }}>
          <div
            style={{
              cursor: "pointer",
              background: "#fff",
              borderRadius: "50%",
              boxShadow: "0 4px 16px rgba(1,84,185,0.18)",
              width: 72,
              height: 72,
              display: "flex",
              alignItems: "center",
              justifyContent: "center",
              margin: "0 auto"
            }}
            onClick={() => setOpen(true)}
          >
            <img src={robotImg} alt="Chatbot" style={{ width: 52, height: 52 }} />
          </div>
          <div style={{
            marginTop: 12,
            background: "#fff",
            color: "#0154b9",
            borderRadius: 12,
            boxShadow: "0 2px 8px rgba(1,84,185,0.10)",
            padding: "10px 18px",
            fontWeight: 500,
            fontSize: 16,
            textAlign: "center",
            maxWidth: 340,
            border: "1.5px solid #e3f0ff"
          }}>
            Xin chào! Tôi là trợ lý Vicnex, sẵn sàng tư vấn các sản phẩm cầu lông cho bạn.
          </div>
        </div>
      )}

      {/* Cửa sổ chat */}
      {open && (
        <div
          style={{
            position: "fixed",
            bottom: 32,
            right: 32,
            zIndex: 1001,
            width: 480,
            maxHeight: 700,
            background: "#fff",
            borderRadius: 22,
            boxShadow: "0 8px 32px rgba(1,84,185,0.18)",
            display: "flex",
            flexDirection: "column",
            overflow: "hidden",
            fontFamily: "'Segoe UI', Arial, sans-serif"
          }}
        >
          {/* Header */}
          <div style={{
            background: "linear-gradient(90deg, #0154b9 70%, #3ba9fc 100%)",
            color: "#fff",
            padding: "18px 28px",
            fontWeight: 700,
            fontSize: 22,
            display: "flex",
            alignItems: "center",
            justifyContent: "space-between",
            borderRadius: "22px 22px 0 0",
            boxShadow: "0 2px 8px rgba(1,84,185,0.08)"
          }}>
            <span style={{display: "flex", alignItems: "center"}}>
              <img src={robotImg} alt="Bot" style={{ width: 34, marginRight: 10 }} /> Trợ lý Vicnex
            </span>
            <button
              style={{
                background: "transparent",
                border: "none",
                color: "#fff",
                fontSize: 28,
                cursor: "pointer"
              }}
              onClick={() => setOpen(false)}
              title="Đóng"
            >×</button>
          </div>
          {/* Nội dung chat */}
          <div style={{
            flex: 1,
            padding: "18px 22px",
            overflowY: "auto",
            background: "#fafdff"
          }}>
            {messages.map((msg, idx) => (
              <div key={idx} style={{
                marginBottom: 18,
                textAlign: msg.from === "user" ? "right" : "left"
              }}>
                {/* Nếu có products, render box sản phẩm */}
                {msg.products && Array.isArray(msg.products) && msg.products.length > 0 ? (
                  msg.products.map((product, i) => (
                    <div key={i} style={{
                      display: "flex",
                      alignItems: "center",
                      background: "#fafdff",
                      borderRadius: 18,
                      boxShadow: "0 2px 12px rgba(1,84,185,0.07)",
                      padding: 18,
                      marginBottom: 16,
                      maxWidth: 420,
                      border: "1.5px solid #e3f0ff"
                    }}>
                      <img src={product.image} alt={product.name} style={{
                        width: 100,
                        height: 100,
                        objectFit: "cover",
                        borderRadius: 12,
                        marginRight: 18,
                        border: "1.5px solid #b6d4fe",
                        background: "#fff"
                      }} />
                      <div style={{ flex: 1 }}>
                        <div style={{
                          fontWeight: 700,
                          color: "#0154b9",
                          fontSize: "1.15em",
                          marginBottom: 6
                        }}>{product.name}</div>
                        <div style={{
                          color: "#d32f2f",
                          fontWeight: 600,
                          marginBottom: 6,
                          fontSize: 18
                        }}>{Number(product.price).toLocaleString()}đ</div>
                        <div style={{
                          fontSize: 15,
                          color: "#444",
                          marginTop: 2
                        }}>{product.description}</div>
                      </div>
                    </div>
                  ))
                ) : (
                  <span style={{
                    display: "inline-block",
                    borderRadius: 16,
                    padding: "12px 20px",
                    maxWidth: "80%",
                    marginBottom: 6,
                    fontSize: 16,
                    lineHeight: 1.6,
                    boxShadow: "0 1px 6px rgba(1,84,185,0.06)",
                    background: msg.from === "user" ? "#e0e7ff" : "#fff",
                    color: msg.from === "user" ? "#0154b9" : "#222",
                    border: msg.from === "user"
                      ? "1.5px solid #b6d4fe"
                      : "1.5px solid #e3f0ff",
                    textAlign: msg.from === "user" ? "right" : "left",
                    float: msg.from === "user" ? "right" : "none",
                    whiteSpace: "pre-line" // <-- thêm dòng này để xuống dòng
                  }}>
                    {msg.text || msg.answer}
                  </span>
                )}
              </div>
            ))}
            {loading && (
              <div style={{ color: "#0154b9", fontStyle: "italic", marginBottom: 8 }}>Đang trả lời...</div>
            )}
          </div>
          {/* Input */}
          <div style={{
            padding: "16px 24px",
            borderTop: "1.5px solid #e0e7ff",
            background: "#fafdff",
            display: "flex",
            gap: 10
          }}>
            <input
              type="text"
              value={input}
              onChange={e => setInput(e.target.value)}
              placeholder="Nhập câu hỏi về cầu lông..."
              style={{
                flex: 1,
                border: "none",
                outline: "none",
                borderRadius: 10,
                padding: "12px 16px",
                fontSize: 16,
                background: "#f6f8fc"
              }}
              onKeyDown={e => e.key === "Enter" && sendMessage()}
              disabled={loading}
            />
            <button
              onClick={sendMessage}
              disabled={loading || !input.trim()}
              style={{
                background: "#0154b9",
                color: "#fff",
                border: "none",
                borderRadius: 10,
                padding: "12px 26px",
                fontWeight: 600,
                fontSize: 16,
                cursor: loading || !input.trim() ? "not-allowed" : "pointer",
                transition: "background 0.18s"
              }}
            >Gửi</button>
          </div>
        </div>
      )}
    </div>
  );
}

export default ChatBotBadminton;