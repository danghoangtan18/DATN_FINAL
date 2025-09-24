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
    <>
      <style>{`
        @keyframes chatBounce {
          0%, 20%, 53%, 80%, 100% { 
            transform: translate3d(0,0,0); 
          }
          40%, 43% { 
            transform: translate3d(0,-8px,0); 
          }
          70% { 
            transform: translate3d(0,-4px,0); 
          }
          90% { 
            transform: translate3d(0,-2px,0); 
          }
        }
        
        @keyframes chatPulse {
          0% { 
            transform: scale(1); 
            box-shadow: 0 8px 32px rgba(1,84,185,0.25); 
          }
          50% { 
            transform: scale(1.05); 
            box-shadow: 0 12px 40px rgba(1,84,185,0.35); 
          }
          100% { 
            transform: scale(1); 
            box-shadow: 0 8px 32px rgba(1,84,185,0.25); 
          }
        }
        
        @keyframes messageSlideIn {
          from { 
            opacity: 0; 
            transform: translateY(20px); 
          }
          to { 
            opacity: 1; 
            transform: translateY(0); 
          }
        }
        
        @keyframes shimmer {
          0% { left: -100%; }
          100% { left: 100%; }
        }
        
        @keyframes typing {
          0%, 60%, 100% { 
            transform: translateY(0); 
          }
          30% { 
            transform: translateY(-10px); 
          }
        }
        
        .chat-button {
          transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .chat-button:hover {
          animation: chatBounce 1s;
          transform: scale(1.1);
        }
        
        .chat-button-pulse {
          animation: chatPulse 2s infinite;
        }
        
        .chat-message {
          animation: messageSlideIn 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        
        .chat-input {
          transition: all 0.2s ease;
        }
        
        .chat-input:focus {
          outline: none;
          border-color: #0154b9;
          box-shadow: 0 0 0 3px rgba(1,84,185,0.15);
        }
        
        .chat-product-card {
          transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
          cursor: pointer;
        }
        
        .chat-product-card:hover {
          transform: translateY(-4px) scale(1.02);
          box-shadow: 0 12px 30px rgba(1,84,185,0.2);
        }
        
        .chat-send-button {
          transition: all 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
          background: linear-gradient(135deg, #0154b9 0%, #0166d9 100%);
        }
        
        .chat-send-button:hover {
          transform: scale(1.05);
          background: linear-gradient(135deg, #004494 0%, #0154b9 100%);
          box-shadow: 0 4px 15px rgba(1,84,185,0.3);
        }
        
        .chat-send-button:active {
          transform: scale(0.95);
        }
        
        .chat-window {
          backdrop-filter: blur(20px);
          background: rgba(255, 255, 255, 0.98);
          border: 1px solid rgba(1,84,185,0.1);
        }
        
        .chat-header {
          background: linear-gradient(135deg, #0154b9 0%, #0166d9 50%, #3ba9fc 100%);
          position: relative;
          overflow: hidden;
        }
        
        .chat-header::before {
          content: '';
          position: absolute;
          top: 0;
          left: -100%;
          width: 100%;
          height: 100%;
          background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
          animation: shimmer 3s infinite;
        }
        
        .typing-indicator {
          display: flex;
          align-items: center;
          gap: 4px;
        }
        
        .typing-dot {
          width: 8px;
          height: 8px;
          border-radius: 50%;
          background: #0154b9;
          animation: typing 1.4s infinite;
        }
        
        .typing-dot:nth-child(2) {
          animation-delay: 0.2s;
        }
        
        .typing-dot:nth-child(3) {
          animation-delay: 0.4s;
        }
        
        .glass-effect {
          background: rgba(255, 255, 255, 0.9);
          backdrop-filter: blur(10px);
          border: 1px solid rgba(255, 255, 255, 0.2);
        }
      `}</style>
      
      <div>
        {/* Floating Chat Button */}
        {!open && (
          <div style={{ 
            position: "fixed", 
            bottom: 24, 
            right: 24, 
            zIndex: 1000,
            display: 'flex',
            flexDirection: 'column',
            alignItems: 'flex-end',
            gap: 12
          }}>
            {/* Welcome Message */}
            <div 
              className="glass-effect"
              style={{
                padding: "16px 20px",
                borderRadius: "20px 20px 4px 20px",
                fontSize: "14px",
                fontWeight: 500,
                maxWidth: 280,
                lineHeight: 1.5,
                boxShadow: "0 8px 32px rgba(1,84,185,0.15)",
                color: "#0154b9",
                position: "relative"
              }}
            >
              <div style={{ 
                display: 'flex', 
                alignItems: 'center', 
                gap: 8, 
                marginBottom: 8,
                fontWeight: 600 
              }}>
                🏸 <span style={{ color: '#0154b9' }}>Vicnex Assistant</span>
              </div>
              <div style={{ color: '#666', fontSize: '13px' }}>
                Chuyên tư vấn đồ cầu lông chính hãng
                <br />
                <span style={{ color: '#0154b9', fontWeight: 600 }}>Click để bắt đầu tư vấn!</span>
              </div>
              
              {/* Arrow pointing to button */}
              <div style={{
                position: "absolute",
                bottom: -6,
                right: 20,
                width: 0,
                height: 0,
                borderLeft: "6px solid transparent",
                borderRight: "6px solid transparent",
                borderTop: "6px solid rgba(255,255,255,0.9)"
              }} />
            </div>
            
            {/* Chat Button */}
            <div
              className="chat-button chat-button-pulse"
              style={{
                cursor: "pointer",
                background: "linear-gradient(135deg, #0154b9 0%, #0166d9 50%, #3ba9fc 100%)",
                borderRadius: "50%",
                width: 68,
                height: 68,
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
                boxShadow: "0 8px 32px rgba(1,84,185,0.3), 0 0 0 0 rgba(1,84,185,0.4)",
                border: "none",
                position: "relative",
                overflow: "hidden"
              }}
              onClick={() => setOpen(true)}
            >
              {/* Shine effect */}
              <div style={{
                position: "absolute",
                top: "-50%",
                left: "-50%",
                width: "200%",
                height: "200%",
                background: "radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%)",
                borderRadius: "50%",
                animation: "chatPulse 3s infinite"
              }} />
              
              {/* Chat icon */}
              <div style={{
                color: "white",
                fontSize: "28px",
                zIndex: 2,
                textShadow: "0 2px 4px rgba(0,0,0,0.2)"
              }}>💬</div>
              
              {/* Notification badge */}
              <div style={{
                position: "absolute",
                top: -2,
                right: -2,
                background: "linear-gradient(135deg, #ff4757 0%, #ff3742 100%)",
                color: "white",
                borderRadius: "50%",
                width: 22,
                height: 22,
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
                fontSize: "12px",
                fontWeight: "bold",
                boxShadow: "0 2px 8px rgba(255,71,87,0.4)",
                border: "2px solid white"
              }}>!</div>
            </div>
          </div>
        )}

        {/* Chat Window */}
        {open && (
          <div
            className="chat-window"
            style={{
              position: "fixed",
              bottom: 24,
              right: 24,
              zIndex: 1001,
              width: 420,
              maxWidth: "calc(100vw - 48px)",
              height: 600,
              maxHeight: "calc(100vh - 48px)",
              borderRadius: 24,
              boxShadow: "0 20px 60px rgba(1,84,185,0.2), 0 0 0 1px rgba(1,84,185,0.1)",
              display: "flex",
              flexDirection: "column",
              overflow: "hidden",
              fontFamily: "'Inter', 'Segoe UI', Arial, sans-serif"
            }}
          >
            {/* Modern Header */}
            <div className="chat-header" style={{
              color: "#fff",
              padding: "20px 24px",
              display: "flex",
              alignItems: "center",
              justifyContent: "space-between",
              borderRadius: "24px 24px 0 0",
              minHeight: 70
            }}>
              <div style={{display: "flex", alignItems: "center", gap: 12}}>
                <div style={{
                  width: 40,
                  height: 40,
                  borderRadius: "50%",
                  background: "rgba(255,255,255,0.2)",
                  display: "flex",
                  alignItems: "center",
                  justifyContent: "center",
                  fontSize: "20px"
                }}>
                  🏸
                </div>
                <div>
                  <div style={{ fontWeight: 700, fontSize: "18px", lineHeight: 1 }}>
                    Vicnex Assistant
                  </div>
                  <div style={{ 
                    fontSize: "13px", 
                    opacity: 0.9, 
                    fontWeight: 500,
                    marginTop: 2
                  }}>
                    Tư vấn viên chuyên nghiệp
                  </div>
                </div>
              </div>
              
              <button
                style={{
                  background: "rgba(255,255,255,0.2)",
                  border: "none",
                  color: "#fff",
                  fontSize: 24,
                  cursor: "pointer",
                  borderRadius: "50%",
                  width: 36,
                  height: 36,
                  display: "flex",
                  alignItems: "center",
                  justifyContent: "center",
                  transition: "all 0.2s ease"
                }}
                onClick={() => setOpen(false)}
                onMouseOver={(e) => {
                  e.target.style.background = "rgba(255,255,255,0.3)";
                  e.target.style.transform = "scale(1.1)";
                }}
                onMouseOut={(e) => {
                  e.target.style.background = "rgba(255,255,255,0.2)";
                  e.target.style.transform = "scale(1)";
                }}
                title="Đóng"
              >×</button>
            </div>
            
            {/* Chat Messages */}
            <div style={{
              flex: 1,
              padding: "20px 24px",
              overflowY: "auto",
              background: "linear-gradient(180deg, #fafbff 0%, #f8faff 100%)",
              maxHeight: "calc(100% - 140px)"
            }}>
              {messages.map((msg, idx) => (
                <div key={idx} className="chat-message" style={{
                  marginBottom: 20,
                  display: "flex",
                  flexDirection: "column",
                  alignItems: msg.from === "user" ? "flex-end" : "flex-start"
                }}>
                  {/* Message bubble */}
                  <div style={{
                    maxWidth: "85%",
                    padding: msg.from === "user" ? "12px 18px" : "16px 20px",
                    borderRadius: msg.from === "user" 
                      ? "20px 20px 4px 20px" 
                      : "20px 20px 20px 4px",
                    background: msg.from === "user" 
                      ? "linear-gradient(135deg, #0154b9 0%, #0166d9 100%)"
                      : "white",
                    color: msg.from === "user" ? "white" : "#333",
                    fontSize: "15px",
                    lineHeight: 1.5,
                    fontWeight: msg.from === "user" ? 500 : 400,
                    boxShadow: msg.from === "user" 
                      ? "0 4px 16px rgba(1,84,185,0.2)"
                      : "0 4px 16px rgba(0,0,0,0.08)",
                    border: msg.from === "bot" ? "1px solid rgba(0,0,0,0.05)" : "none"
                  }}>
                    {msg.text.split('\n').map((line, i) => (
                      <div key={i} style={{ marginBottom: i < msg.text.split('\n').length - 1 ? 8 : 0 }}>
                        {line}
                      </div>
                    ))}
                  </div>
                  
                  {/* Product recommendations */}
                  {msg.products && Array.isArray(msg.products) && msg.products.length > 0 && (
                    <div style={{ 
                      marginTop: 12, 
                      display: "flex", 
                      flexDirection: "column", 
                      gap: 12,
                      width: "100%"
                    }}>
                      {msg.products.map((product, i) => (
                        <div 
                          key={i} 
                          className="chat-product-card"
                          style={{
                            display: "flex",
                            alignItems: "center",
                            background: "white",
                            borderRadius: 16,
                            padding: 16,
                            boxShadow: "0 4px 20px rgba(1,84,185,0.08)",
                            border: "1px solid rgba(1,84,185,0.1)",
                            maxWidth: "100%"
                          }}
                        >
                          <img 
                            src={product.image} 
                            alt={product.name} 
                            style={{
                              width: 80,
                              height: 80,
                              objectFit: "cover",
                              borderRadius: 12,
                              marginRight: 16,
                              border: "1px solid rgba(0,0,0,0.1)"
                            }} 
                          />
                          <div style={{ flex: 1 }}>
                            <div style={{
                              fontWeight: 600,
                              color: "#0154b9",
                              fontSize: "16px",
                              marginBottom: 6,
                              lineHeight: 1.3
                            }}>
                              {product.name}
                            </div>
                            <div style={{
                              color: "#d32f2f",
                              fontWeight: 700,
                              marginBottom: 8,
                              fontSize: 18
                            }}>
                              {Number(product.price).toLocaleString('vi-VN')}₫
                              {product.original_price && product.original_price > product.price && (
                                <span style={{
                                  color: "#999",
                                  fontSize: 14,
                                  fontWeight: 400,
                                  textDecoration: "line-through",
                                  marginLeft: 8
                                }}>
                                  {Number(product.original_price).toLocaleString('vi-VN')}₫
                                </span>
                              )}
                            </div>
                            <div style={{
                              color: "#666",
                              fontSize: 13,
                              lineHeight: 1.4,
                              marginBottom: 10
                            }}>
                              {product.description}
                            </div>
                            <button style={{
                              background: "linear-gradient(135deg, #0154b9 0%, #0166d9 100%)",
                              color: "white",
                              border: "none",
                              borderRadius: 8,
                              padding: "8px 16px",
                              fontSize: 13,
                              fontWeight: 600,
                              cursor: "pointer",
                              transition: "all 0.2s ease"
                            }}
                            onMouseOver={(e) => {
                              e.target.style.transform = "scale(1.05)";
                            }}
                            onMouseOut={(e) => {
                              e.target.style.transform = "scale(1)";
                            }}>
                              Xem chi tiết →
                            </button>
                          </div>
                        </div>
                      ))}
                    </div>
                  )}
                </div>
              ))}
              
              {/* Typing indicator */}
              {loading && (
                <div className="chat-message" style={{
                  display: "flex",
                  alignItems: "center",
                  marginBottom: 20
                }}>
                  <div style={{
                    background: "white",
                    borderRadius: "20px 20px 20px 4px",
                    padding: "16px 20px",
                    boxShadow: "0 4px 16px rgba(0,0,0,0.08)",
                    border: "1px solid rgba(0,0,0,0.05)"
                  }}>
                    <div className="typing-indicator">
                      <div className="typing-dot"></div>
                      <div className="typing-dot"></div>
                      <div className="typing-dot"></div>
                    </div>
                  </div>
                </div>
              )}
            </div>
            
            {/* Modern Input */}
            <div style={{
              padding: "20px 24px",
              background: "white",
              borderTop: "1px solid rgba(1,84,185,0.1)",
              borderRadius: "0 0 24px 24px"
            }}>
              <div style={{
                display: "flex",
                alignItems: "center",
                gap: 12,
                background: "#f8faff",
                borderRadius: 16,
                padding: "4px 4px 4px 20px",
                border: "2px solid transparent"
              }}>
                <input
                  className="chat-input"
                  type="text"
                  placeholder="Nhập câu hỏi của bạn..."
                  value={input}
                  onChange={(e) => setInput(e.target.value)}
                  onKeyPress={(e) => e.key === "Enter" && sendMessage()}
                  style={{
                    flex: 1,
                    border: "none",
                    background: "transparent",
                    fontSize: "15px",
                    outline: "none",
                    padding: "12px 0",
                    color: "#333"
                  }}
                  disabled={loading}
                />
                <button
                  className="chat-send-button"
                  onClick={sendMessage}
                  disabled={loading || !input.trim()}
                  style={{
                    border: "none",
                    borderRadius: 12,
                    padding: "12px 16px",
                    color: "white",
                    fontSize: "16px",
                    cursor: loading || !input.trim() ? "not-allowed" : "pointer",
                    display: "flex",
                    alignItems: "center",
                    justifyContent: "center",
                    minWidth: 48,
                    opacity: loading || !input.trim() ? 0.5 : 1
                  }}
                >
                  {loading ? "⏳" : "🚀"}
                </button>
              </div>
            </div>
          </div>
        )}
      </div>
    </>
  );
}

export default ChatBotBadminton;