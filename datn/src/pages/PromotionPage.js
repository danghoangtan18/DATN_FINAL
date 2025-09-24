import React, { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import Header from "../components/home/Header";
import Footer from "../components/home/Footer";
import BreadcrumbNav from "../components/product/BreadcrumbNav";
import SectionHeading from "../components/home/SectionHeading";

function PromotionPage() {
  const navigate = useNavigate();
  const [promotions, setPromotions] = useState([]);
  const [discountedProducts, setDiscountedProducts] = useState([]);
  const [flashSales, setFlashSales] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // Fetch promotion data
    const fetchPromotions = async () => {
      try {
        // Get products with discounts
        const discountResponse = await fetch("http://localhost:8000/api/products?has_discount=1");
        const discountData = await discountResponse.json();
        setDiscountedProducts(discountData.data || []);

        // Get flash sale products
        const flashResponse = await fetch("http://localhost:8000/api/flash-sales");
        if (flashResponse.ok) {
          const flashData = await flashResponse.json();
          setFlashSales(flashData.data || []);
        }

        // Mock promotions data - bạn có thể thay bằng API thật
        setPromotions([
          {
            id: 1,
            title: "🔥 Flash Sale Cuối Tuần",
            description: "Giảm giá lên đến 50% cho các sản phẩm vợt cầu lông",
            discount: "50%",
            validFrom: "2025-09-24",
            validTo: "2025-09-26",
            image: "https://via.placeholder.com/400x200/ff6b35/ffffff?text=Flash+Sale",
            type: "flash_sale",
            color: "#ff6b35"
          },
          {
            id: 2,
            title: "🎯 Khuyến Mãi Thương Hiệu",
            description: "Mua 2 tặng 1 cho tất cả sản phẩm Yonex",
            discount: "Buy 2 Get 1",
            validFrom: "2025-09-20",
            validTo: "2025-09-30",
            image: "https://via.placeholder.com/400x200/4dabf7/ffffff?text=Brand+Sale",
            type: "brand_sale",
            color: "#4dabf7"
          },
          {
            id: 3,
            title: "🏆 Ưu Đãi Thành Viên VIP",
            description: "Giảm thêm 15% cho thành viên VIP từ lần mua thứ 5",
            discount: "15%",
            validFrom: "2025-09-01",
            validTo: "2025-10-31",
            image: "https://via.placeholder.com/400x200/51cf66/ffffff?text=VIP+Sale",
            type: "vip_sale",
            color: "#51cf66"
          },
          {
            id: 4,
            title: "🎁 Combo Tiết Kiệm",
            description: "Mua vợt + giày + áo đồng phục giảm 30%",
            discount: "30%",
            validFrom: "2025-09-15",
            validTo: "2025-10-15",
            image: "https://via.placeholder.com/400x200/9775fa/ffffff?text=Combo+Deal",
            type: "combo_sale",
            color: "#9775fa"
          }
        ]);

        setLoading(false);
      } catch (error) {
        console.error("Error fetching promotions:", error);
        setLoading(false);
      }
    };

    fetchPromotions();
  }, []);

  const calculateTimeLeft = (endDate) => {
    const difference = +new Date(endDate) - +new Date();
    let timeLeft = {};

    if (difference > 0) {
      timeLeft = {
        days: Math.floor(difference / (1000 * 60 * 60 * 24)),
        hours: Math.floor((difference / (1000 * 60 * 60)) % 24),
        minutes: Math.floor((difference / 1000 / 60) % 60),
        seconds: Math.floor((difference / 1000) % 60)
      };
    }

    return timeLeft;
  };

  const formatPrice = (price) => {
    return new Intl.NumberFormat('vi-VN', {
      style: 'currency',
      currency: 'VND'
    }).format(price);
  };

  const handleProductClick = (slug) => {
    navigate(`/product/${slug}`);
  };

  if (loading) {
    return (
      <div>
        <Header />
        <div style={{ padding: "50px", textAlign: "center" }}>
          <div style={{ fontSize: "24px", color: "#666" }}>Đang tải khuyến mãi...</div>
        </div>
        <Footer />
      </div>
    );
  }

  return (
    <div>
      <Header />
      
      {/* Breadcrumb */}
      <BreadcrumbNav 
        items={[
          { name: "Trang chủ", path: "/" },
          { name: "Khuyến mãi", path: "/promotions" }
        ]} 
      />

      {/* Page Heading */}
      <SectionHeading 
        title="🎉 Khuyến Mãi Đặc Biệt" 
        subtitle="Cơ hội vàng để sở hữu những sản phẩm cầu lông chất lượng với giá ưu đãi nhất!"
      />

      <div style={{ padding: "0 20px", maxWidth: "1200px", margin: "0 auto" }}>
        
        {/* Hero Promotion Banner */}
        <div style={{
          background: "linear-gradient(135deg, #ff6b35 0%, #f7931e 100%)",
          borderRadius: "20px",
          padding: "40px",
          margin: "30px 0",
          color: "white",
          textAlign: "center",
          position: "relative",
          overflow: "hidden"
        }}>
          <div style={{
            position: "absolute",
            top: "-50px",
            right: "-50px",
            width: "150px",
            height: "150px",
            background: "rgba(255,255,255,0.1)",
            borderRadius: "50%"
          }}></div>
          <h1 style={{ fontSize: "48px", margin: "0 0 10px 0", fontWeight: "bold" }}>
            🔥 MEGA SALE 2025
          </h1>
          <p style={{ fontSize: "24px", margin: "0 0 20px 0", opacity: 0.9 }}>
            Giảm giá lên đến <strong>70%</strong> cho tất cả sản phẩm
          </p>
          <div style={{ fontSize: "18px", opacity: 0.8 }}>
            Thời gian có hạn - Nhanh tay để không bỏ lỡ!
          </div>
        </div>

        {/* Active Promotions Grid */}
        <section style={{ margin: "50px 0" }}>
          <h2 style={{
            fontSize: "32px",
            fontWeight: "bold",
            textAlign: "center",
            margin: "0 0 30px 0",
            color: "#333"
          }}>
            🎯 Chương Trình Khuyến Mãi Hiện Tại
          </h2>
          
          <div style={{
            display: "grid",
            gridTemplateColumns: "repeat(auto-fit, minmax(350px, 1fr))",
            gap: "25px",
            margin: "30px 0"
          }}>
            {promotions.map((promo) => {
              const timeLeft = calculateTimeLeft(promo.validTo);
              const isActive = Object.keys(timeLeft).length > 0;
              
              return (
                <div key={promo.id} style={{
                  background: "white",
                  borderRadius: "15px",
                  padding: "0",
                  boxShadow: "0 8px 25px rgba(0,0,0,0.1)",
                  transition: "all 0.3s ease",
                  cursor: "pointer",
                  overflow: "hidden",
                  border: `3px solid ${promo.color}20`
                }}
                onMouseEnter={(e) => {
                  e.currentTarget.style.transform = "translateY(-5px)";
                  e.currentTarget.style.boxShadow = "0 15px 35px rgba(0,0,0,0.15)";
                }}
                onMouseLeave={(e) => {
                  e.currentTarget.style.transform = "translateY(0)";
                  e.currentTarget.style.boxShadow = "0 8px 25px rgba(0,0,0,0.1)";
                }}>
                  
                  {/* Promotion Header */}
                  <div style={{
                    background: `linear-gradient(135deg, ${promo.color} 0%, ${promo.color}dd 100%)`,
                    padding: "20px",
                    color: "white",
                    position: "relative"
                  }}>
                    <div style={{
                      position: "absolute",
                      top: "10px",
                      right: "15px",
                      background: "rgba(255,255,255,0.2)",
                      padding: "5px 15px",
                      borderRadius: "20px",
                      fontSize: "14px",
                      fontWeight: "bold"
                    }}>
                      {isActive ? "🔥 ĐANG DIỄN RA" : "⏰ ĐÃ KẾT THÚC"}
                    </div>
                    
                    <h3 style={{ 
                      margin: "0 0 8px 0", 
                      fontSize: "24px", 
                      fontWeight: "bold" 
                    }}>
                      {promo.title}
                    </h3>
                    
                    <p style={{ 
                      margin: "0", 
                      fontSize: "16px", 
                      opacity: 0.95,
                      lineHeight: 1.4 
                    }}>
                      {promo.description}
                    </p>
                  </div>

                  {/* Promotion Body */}
                  <div style={{ padding: "25px" }}>
                    <div style={{
                      display: "flex",
                      justifyContent: "space-between",
                      alignItems: "center",
                      marginBottom: "20px"
                    }}>
                      <div>
                        <div style={{ 
                          fontSize: "14px", 
                          color: "#666", 
                          marginBottom: "5px" 
                        }}>
                          Mức giảm giá
                        </div>
                        <div style={{
                          fontSize: "32px",
                          fontWeight: "bold",
                          color: promo.color
                        }}>
                          {promo.discount}
                        </div>
                      </div>
                      
                      {/* Countdown Timer */}
                      {isActive && (
                        <div style={{ textAlign: "right" }}>
                          <div style={{ 
                            fontSize: "14px", 
                            color: "#666", 
                            marginBottom: "5px" 
                          }}>
                            Còn lại
                          </div>
                          <div style={{
                            display: "flex",
                            gap: "5px",
                            fontSize: "14px",
                            fontWeight: "bold"
                          }}>
                            <span style={{
                              background: "#ff6b35",
                              color: "white",
                              padding: "3px 8px",
                              borderRadius: "5px",
                              minWidth: "25px"
                            }}>
                              {timeLeft.days || 0}d
                            </span>
                            <span style={{
                              background: "#ff6b35",
                              color: "white",
                              padding: "3px 8px", 
                              borderRadius: "5px",
                              minWidth: "25px"
                            }}>
                              {timeLeft.hours || 0}h
                            </span>
                            <span style={{
                              background: "#ff6b35",
                              color: "white",
                              padding: "3px 8px",
                              borderRadius: "5px", 
                              minWidth: "25px"
                            }}>
                              {timeLeft.minutes || 0}m
                            </span>
                          </div>
                        </div>
                      )}
                    </div>

                    <div style={{
                      fontSize: "14px",
                      color: "#888",
                      marginBottom: "15px"
                    }}>
                      📅 Từ {new Date(promo.validFrom).toLocaleDateString('vi-VN')} 
                      đến {new Date(promo.validTo).toLocaleDateString('vi-VN')}
                    </div>

                    <button style={{
                      width: "100%",
                      padding: "12px",
                      background: isActive ? promo.color : "#ccc",
                      color: "white",
                      border: "none",
                      borderRadius: "8px",
                      fontSize: "16px",
                      fontWeight: "bold",
                      cursor: isActive ? "pointer" : "not-allowed",
                      transition: "all 0.3s ease"
                    }}
                    disabled={!isActive}
                    onClick={() => {
                      if (isActive) {
                        navigate("/products");
                      }
                    }}>
                      {isActive ? "🛍️ Mua Ngay" : "Đã Hết Hạn"}
                    </button>
                  </div>
                </div>
              );
            })}
          </div>
        </section>

        {/* Discounted Products Section */}
        {discountedProducts.length > 0 && (
          <section style={{ margin: "50px 0" }}>
            <h2 style={{
              fontSize: "32px",
              fontWeight: "bold",
              textAlign: "center",
              margin: "0 0 30px 0",
              color: "#333"
            }}>
              🏷️ Sản Phẩm Đang Khuyến Mãi
            </h2>
            
            <div style={{
              display: "grid",
              gridTemplateColumns: "repeat(auto-fit, minmax(280px, 1fr))",
              gap: "20px",
              margin: "30px 0"
            }}>
              {discountedProducts.slice(0, 8).map((product) => {
                const discountPercent = Math.round(
                  ((product.Price - product.Discount_price) / product.Price) * 100
                );
                
                return (
                  <div key={product.Product_ID} style={{
                    background: "white",
                    borderRadius: "15px",
                    padding: "0",
                    boxShadow: "0 5px 15px rgba(0,0,0,0.08)",
                    transition: "all 0.3s ease",
                    cursor: "pointer",
                    overflow: "hidden",
                    position: "relative"
                  }}
                  onClick={() => handleProductClick(product.slug)}
                  onMouseEnter={(e) => {
                    e.currentTarget.style.transform = "translateY(-3px)";
                    e.currentTarget.style.boxShadow = "0 10px 25px rgba(0,0,0,0.12)";
                  }}
                  onMouseLeave={(e) => {
                    e.currentTarget.style.transform = "translateY(0)";
                    e.currentTarget.style.boxShadow = "0 5px 15px rgba(0,0,0,0.08)";
                  }}>
                    
                    {/* Discount Badge */}
                    <div style={{
                      position: "absolute",
                      top: "10px",
                      left: "10px",
                      background: "#ff4757",
                      color: "white",
                      padding: "5px 12px",
                      borderRadius: "20px",
                      fontSize: "12px",
                      fontWeight: "bold",
                      zIndex: 2
                    }}>
                      -{discountPercent}%
                    </div>

                    {/* Product Image */}
                    {product.Image ? (
                      <img
                        src={`http://localhost:8000/${encodeURI(product.Image)}`}
                        alt={product.Name}
                        style={{
                          width: "100%",
                          height: "200px",
                          objectFit: "cover",
                          backgroundColor: "#f8f9fa"
                        }}
                        onError={(e) => {
                          e.target.src = "https://via.placeholder.com/280x200/f8f9fa/666666?text=No+Image";
                        }}
                      />
                    ) : (
                      <div style={{
                        width: "100%",
                        height: "200px",
                        background: "#f8f9fa",
                        display: "flex",
                        alignItems: "center",
                        justifyContent: "center",
                        color: "#666",
                        fontSize: "14px"
                      }}>
                        Không có hình ảnh
                      </div>
                    )}

                    {/* Product Info */}
                    <div style={{ padding: "20px" }}>
                      <h4 style={{
                        margin: "0 0 10px 0",
                        fontSize: "16px",
                        fontWeight: "600",
                        color: "#333",
                        display: "-webkit-box",
                        WebkitLineClamp: 2,
                        WebkitBoxOrient: "vertical",
                        overflow: "hidden"
                      }}>
                        {product.Name}
                      </h4>

                      <div style={{ margin: "10px 0" }}>
                        <span style={{
                          fontSize: "18px",
                          fontWeight: "bold",
                          color: "#ff4757"
                        }}>
                          {formatPrice(product.Discount_price)}
                        </span>
                        <span style={{
                          fontSize: "14px",
                          color: "#999",
                          textDecoration: "line-through",
                          marginLeft: "8px"
                        }}>
                          {formatPrice(product.Price)}
                        </span>
                      </div>

                      <div style={{
                        fontSize: "12px",
                        color: "#666",
                        marginBottom: "15px"
                      }}>
                        Tiết kiệm: {formatPrice(product.Price - product.Discount_price)}
                      </div>

                      <button style={{
                        width: "100%",
                        padding: "8px",
                        background: "#ff4757",
                        color: "white",
                        border: "none",
                        borderRadius: "6px",
                        fontSize: "14px",
                        fontWeight: "bold",
                        cursor: "pointer"
                      }}>
                        Xem Chi Tiết
                      </button>
                    </div>
                  </div>
                );
              })}
            </div>

            <div style={{ textAlign: "center", margin: "30px 0" }}>
              <button 
                onClick={() => navigate("/products?promotion=true")}
                style={{
                  padding: "15px 40px",
                  background: "#ff6b35",
                  color: "white",
                  border: "none",
                  borderRadius: "25px",
                  fontSize: "16px",
                  fontWeight: "bold",
                  cursor: "pointer",
                  transition: "all 0.3s ease"
                }}
                onMouseEnter={(e) => {
                  e.target.style.background = "#e55a2b";
                  e.target.style.transform = "translateY(-2px)";
                }}
                onMouseLeave={(e) => {
                  e.target.style.background = "#ff6b35";
                  e.target.style.transform = "translateY(0)";
                }}
              >
                Xem Tất Cả Sản Phẩm Khuyến Mãi →
              </button>
            </div>
          </section>
        )}

        {/* Newsletter Signup */}
        <section style={{
          background: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)",
          borderRadius: "20px",
          padding: "40px",
          margin: "50px 0",
          color: "white",
          textAlign: "center"
        }}>
          <h3 style={{ 
            fontSize: "28px", 
            margin: "0 0 15px 0", 
            fontWeight: "bold" 
          }}>
            📧 Đăng Ký Nhận Tin Khuyến Mãi
          </h3>
          <p style={{ 
            fontSize: "16px", 
            margin: "0 0 25px 0", 
            opacity: 0.9 
          }}>
            Là người đầu tiên biết về những chương trình khuyến mãi hấp dẫn!
          </p>
          <div style={{
            display: "flex",
            gap: "15px",
            maxWidth: "500px",
            margin: "0 auto",
            flexWrap: "wrap",
            justifyContent: "center"
          }}>
            <input
              type="email"
              placeholder="Nhập địa chỉ email của bạn"
              style={{
                flex: "1",
                minWidth: "250px",
                padding: "12px 20px",
                border: "none",
                borderRadius: "25px",
                fontSize: "14px",
                outline: "none"
              }}
            />
            <button style={{
              padding: "12px 30px",
              background: "#ff4757",
              color: "white",
              border: "none",
              borderRadius: "25px",
              fontSize: "14px",
              fontWeight: "bold",
              cursor: "pointer",
              whiteSpace: "nowrap"
            }}>
              Đăng Ký Ngay
            </button>
          </div>
        </section>

      </div>

      <Footer />
    </div>
  );
}

export default PromotionPage;