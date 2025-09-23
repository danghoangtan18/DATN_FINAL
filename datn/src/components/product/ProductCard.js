import React, { useState, useEffect, useRef } from 'react';
import { useNavigate } from "react-router-dom";

// SỬ DỤNG CÙNG CÁC HELPER FUNCTIONS NHU PRODUCTLIST
function parseVariantName(variantName) {
  return variantName.split(" - ").map(part => part.trim());
}

function getOptionsByPosition(variants, pos) {
  return Array.from(
    new Set(
      variants
        .map(v => {
          const parts = parseVariantName(v.Variant_name);
          return parts[pos];
        })
        .filter(Boolean)
    )
  );
}

function findVariant(variants, selectedOptions) {
  // Nếu chưa chọn gì, return null
  const hasSelectedOptions = Object.values(selectedOptions).some(option => option && option.trim() !== "");
  if (!hasSelectedOptions) {
    return null;
  }

  return variants.find(v => {
    const parts = parseVariantName(v.Variant_name);
    
    // Kiểm tra từng phần được chọn
    const matches = [];
    
    if (selectedOptions.weight && selectedOptions.weight.trim() !== "") {
      matches.push(parts[0] === selectedOptions.weight);
    }
    if (selectedOptions.stiffness && selectedOptions.stiffness.trim() !== "") {
      matches.push(parts[1] === selectedOptions.stiffness);
    }
    if (selectedOptions.balance && selectedOptions.balance.trim() !== "") {
      matches.push(parts[2] === selectedOptions.balance);
    }
    if (selectedOptions.tension && selectedOptions.tension.trim() !== "") {
      matches.push(parts[3] === selectedOptions.tension);
    }
    if (selectedOptions.playStyle && selectedOptions.playStyle.trim() !== "") {
      matches.push(parts[4] === selectedOptions.playStyle);
    }
    
    // Tất cả các phần được chọn phải match
    return matches.length > 0 && matches.every(match => match === true);
  });
}

function getStockInfo(product) {
  const hasValidVariants = product.variants && 
                          Array.isArray(product.variants) && 
                          product.variants.length > 0;
  
  if (hasValidVariants) {
    const availableVariants = product.variants.filter(v => {
      const quantity = parseInt(v.Quantity || 0, 10);
      return quantity > 0;
    });
    
    const totalQuantity = product.variants.reduce((sum, v) => {
      return sum + (parseInt(v.Quantity || 0, 10));
    }, 0);
    
    return {
      hasVariants: true,
      hasStock: availableVariants.length > 0,
      totalQuantity,
      availableCount: availableVariants.length,
      totalCount: product.variants.length,
      displayText: availableVariants.length > 0 
        ? `${availableVariants.length}/${product.variants.length} tùy chọn có sẵn`
        : "Tất cả tùy chọn hết hàng"
    };
  } else {
    const quantity = parseInt(product.Quantity || 0, 10);
    return {
      hasVariants: false,
      hasStock: quantity > 0,
      totalQuantity: quantity,
      availableCount: 0,
      totalCount: 0,
      displayText: quantity > 0 ? `Còn ${quantity} sản phẩm` : "Hết hàng"
    };
  }
}

function calculateRating(product) {
  if (!product.ratings || !Array.isArray(product.ratings) || product.ratings.length === 0) {
    return {
      averageRating: 0,
      totalRatings: 0,
      displayText: "Chưa có đánh giá"
    };
  }

  const totalRating = product.ratings.reduce((sum, rating) => {
    return sum + (parseInt(rating.Rating || 0, 10));
  }, 0);
  
  const averageRating = totalRating / product.ratings.length;
  
  return {
    averageRating: averageRating,
    totalRatings: product.ratings.length,
    displayText: `${averageRating.toFixed(1)} (${product.ratings.length} đánh giá)`
  };
}

const ProductCard = ({ product, onAddCompare, compareProducts = [] }) => {
  const navigate = useNavigate();
  const cardRef = useRef(null);
  
  // DETECT CONTAINER TYPE
  const [isInRecentlyViewed, setIsInRecentlyViewed] = useState(false);
  
  useEffect(() => {
    if (cardRef.current) {
      const recentlyViewedContainer = cardRef.current.closest('.recently-viewed-track');
      setIsInRecentlyViewed(!!recentlyViewedContainer);
    }
  }, []);

  // THÊM STATE GIỐNG PRODUCTLIST
  const [showVariantPopup, setShowVariantPopup] = useState(false);
  const [selectedOptions, setSelectedOptions] = useState({
    weight: "",
    stiffness: "",
    balance: "",
    tension: "",
    playStyle: ""
  });
  const [selectedVariant, setSelectedVariant] = useState(null);

  // TÍNH TOÁN STOCK VÀ RATING
  const stockInfo = getStockInfo(product);
  const ratingInfo = calculateRating(product);
  const isInCompare = compareProducts.some(p => p.Product_ID === product.Product_ID);
  const canAddToCompare = !isInCompare && compareProducts.length < 2;

  const handleProductClick = () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
    navigate(`/product/${product.slug}`);
  };

  // IMPROVED ADD TO CART GIỐNG PRODUCTLIST
  const handleAddToCart = (e) => {
    e.stopPropagation();
    
    if (!product || !product.Product_ID) {
      alert("Thông tin sản phẩm không hợp lệ!");
      return;
    }

    if (!product.Status) {
      alert("Sản phẩm hiện không khả dụng!");
      return;
    }

    if (stockInfo.hasVariants) {
      const hasAvailableVariants = product.variants.some(v => parseInt(v.Quantity || 0, 10) > 0);
      
      if (!hasAvailableVariants) {
        alert("Tất cả tùy chọn của sản phẩm này đã hết hàng!");
        return;
      }
      
      setShowVariantPopup(true);
    } else {
      if (!stockInfo.hasStock) {
        alert("Sản phẩm đã hết hàng!");
        return;
      }
      
      addToCart(product);
    }
  };

  const addToCart = (product, variant = null) => {
    try {
      const cart = JSON.parse(localStorage.getItem("cart") || "[]");
      let item;
      
      if (variant) {
        const variantQuantity = parseInt(variant.Quantity || 0, 10);
        if (variantQuantity <= 0) {
          alert("Tùy chọn này đã hết hàng!");
          return;
        }
        
        item = {
          ...product,
          selectedVariant: variant,
          SKU: variant.SKU,
          Price: Number(variant.Price || product.Price),
          Discount_price: Number(variant.Discount_price || variant.Price),
          quantity: 1,
          cartId: `${product.Product_ID}_${variant.Variant_ID}`
        };
      } else {
        const mainQuantity = parseInt(product.Quantity || 0, 10);
        if (mainQuantity <= 0) {
          alert("Sản phẩm đã hết hàng!");
          return;
        }
        
        item = {
          ...product,
          Price: Number(product.Discount_price || product.Price),
          Discount_price: Number(product.Discount_price),
          quantity: 1,
          cartId: `${product.Product_ID}_main`
        };
      }
      
      const existingItemIndex = cart.findIndex(i => i.cartId === item.cartId);
      
      let updatedCart;
      if (existingItemIndex !== -1) {
        updatedCart = cart.map((cartItem, index) =>
          index === existingItemIndex
            ? { ...cartItem, quantity: (cartItem.quantity || 1) + 1 }
            : cartItem
        );
      } else {
        updatedCart = [...cart, item];
      }
      
      localStorage.setItem("cart", JSON.stringify(updatedCart));
      window.dispatchEvent(new Event("cartUpdated"));
      
      setShowVariantPopup(false);
      setSelectedVariant(null);
      setSelectedOptions({
        weight: "",
        stiffness: "",
        balance: "",
        tension: "",
        playStyle: ""
      });
      
      alert("✅ Đã thêm vào giỏ hàng!");
      
    } catch (error) {
      alert("Có lỗi xảy ra khi thêm vào giỏ hàng!");
    }
  };

  const updateSelectedOption = (key, value) => {
    setSelectedOptions(prev => ({
      ...prev,
      [key]: value
    }));
  };

  // TỰ ĐỘNG TÌM VARIANT KHI THAY ĐỔI OPTIONS
  useEffect(() => {
    if (product.variants && product.variants.length > 0) {
      const variant = findVariant(product.variants, selectedOptions);
      console.log("Selected options:", selectedOptions);
      console.log("Found variant:", variant);
      setSelectedVariant(variant || null);
    }
  }, [selectedOptions, product.variants]);

  // MUA NGAY GIỐNG PRODUCTLIST
  const handleBuyNow = (e) => {
    e.stopPropagation();
    handleAddToCart(e);
    setTimeout(() => {
      navigate("/cart");
    }, 500);
  };

  return (
    <>
      {/* PRODUCT CARD với conditional wrapper */}
      <div
        ref={cardRef}
        className="product-list-item"
        style={{ 
          cursor: "pointer",
          // Conditional styling for recently viewed
          ...(isInRecentlyViewed && {
            width: '200px',
            minWidth: '200px',
            maxWidth: '200px',
            marginRight: '20px',
            marginBottom: '0',
            flexShrink: 0
          })
        }}
        onClick={handleProductClick}
      >
        {/* Trái tim yêu thích */}
        <div className="product-list-fav tooltip-parent">
          ♡
          <span className="tooltip">Yêu thích</span>
        </div>
        
        {/* Ribbon trạng thái */}
        <div className="product-list-ribbons">
          {product.is_hot && (
            <div className="product-list-ribbon hot">HOT</div>
          )}
          {product.is_best_seller && (
            <div className="product-list-ribbon best">BEST</div>
          )}
          {product.is_featured && (
            <div className="product-list-ribbon featured">FEATURED</div>
          )}
        </div>
        
        {/* Ảnh sản phẩm */}
        <div className="product-image-container" style={{
          position: 'relative',
          width: '100%',
          height: isInRecentlyViewed ? '120px' : '200px', // CONDITIONAL HEIGHT
          overflow: 'hidden',
          borderRadius: '12px',
          background: '#f8fafc'
        }}>
          <img
            key={`product-image-${product.Product_ID}-${Date.now()}`}
            src={`/${product.Image}?v=${Date.now()}`}
            alt={product.Name}
            className="product-list-image"
            loading="lazy"
            style={{
              position: 'absolute',
              top: 0,
              left: 0,
              width: '100%',
              height: '100%',
              objectFit: 'cover',
              transition: 'opacity 0.3s ease',
              opacity: 0
            }}
            onLoad={(e) => {
              e.target.style.display = 'block';
              e.target.style.opacity = '1';
            }}
            onError={(e) => {
              e.target.style.display = 'none';
              
              const container = e.target.parentNode;
              if (container.querySelector('.image-placeholder')) {
                return;
              }
              
              const placeholder = document.createElement('div');
              placeholder.className = 'image-placeholder';
              placeholder.style.cssText = `
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                border: 2px dashed #cbd5e0;
                color: #64748b;
                font-size: ${isInRecentlyViewed ? '12px' : '14px'};
                font-weight: 500;
                text-align: center;
                line-height: 1.4;
                transition: all 0.3s ease;
                cursor: default;
                border-radius: 12px;
                box-sizing: border-box;
              `;
              
              const iconSize = isInRecentlyViewed ? 32 : 48;
              const fontSize = isInRecentlyViewed ? 10 : 12;
              
              placeholder.innerHTML = `
                <div style="
                  font-size: ${iconSize}px; 
                  margin-bottom: 4px; 
                  opacity: 0.6;
                  filter: grayscale(0.3);
                ">🏸</div>
                <div style="font-weight: 600; margin-bottom: 2px; font-size: ${fontSize}px;">
                  Hình ảnh sản phẩm
                </div>
                <div style="
                  font-size: ${fontSize - 1}px; 
                  opacity: 0.7;
                  background: rgba(100,116,139,0.1);
                  padding: 2px 8px;
                  border-radius: 8px;
                ">
                  Đang cập nhật...
                </div>
              `;
              
              placeholder.addEventListener('mouseenter', function() {
                this.style.background = 'linear-gradient(135deg, #e2e8f0 0%, #cbd5e0 100%)';
                this.style.transform = 'scale(1.02)';
              });
              
              placeholder.addEventListener('mouseleave', function() {
                this.style.background = 'linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%)';
                this.style.transform = 'scale(1)';
              });
              
              container.appendChild(placeholder);
            }}
            onLoadStart={(e) => {
              e.target.style.opacity = '0';
            }}
          />
        </div>
        
        {/* Nội dung sản phẩm */}
        <div className="product-list-info" style={{
          padding: isInRecentlyViewed ? '0 8px' : '0 10px',
          gap: isInRecentlyViewed ? '2px' : '3px'
        }}>
          <h3 className="product-list-name" style={{
            fontSize: isInRecentlyViewed ? '14px' : '18px',
            minHeight: isInRecentlyViewed ? '28px' : '36px',
            lineHeight: isInRecentlyViewed ? '1.2' : '1.3',
            marginBottom: isInRecentlyViewed ? '4px' : '2px'
          }}>
            {product.Name}
          </h3>
          
          <div className="product-list-category" style={{
            fontSize: isInRecentlyViewed ? '11px' : '0.93rem',
            marginBottom: isInRecentlyViewed ? '2px' : '1px'
          }}>
            {product?.category?.Name || ""}
          </div>
          
          <div className="product-list-brand" style={{
            fontSize: isInRecentlyViewed ? '11px' : '0.97rem',
            marginBottom: isInRecentlyViewed ? '2px' : '2px'
          }}>
            Thương hiệu: {product.Brand || ""}
          </div>
          
          {/* STOCK DISPLAY */}
          <div className="product-list-stock" style={{ 
            margin: isInRecentlyViewed ? "2px 0" : "4px 0", 
            fontSize: isInRecentlyViewed ? "11px" : "0.85em",
            color: stockInfo.hasStock ? "#28a745" : "#dc3545",
            fontWeight: "500"
          }}>
            {stockInfo.displayText}
            {stockInfo.hasVariants && stockInfo.totalQuantity > 0 && (
              <span style={{ 
                color: "#666", 
                fontSize: "0.9em", 
                marginLeft: "5px" 
              }}>
                (Tổng: {stockInfo.totalQuantity})
              </span>
            )}
          </div>
          
          {/* Giá sản phẩm */}
          <div className="product-list-price" style={{
            fontSize: isInRecentlyViewed ? '13px' : '15px',
            marginBottom: isInRecentlyViewed ? '3px' : '4px'
          }}>
            {product.Discount_price && Number(product.Discount_price) < Number(product.Price) ? (
              <>
                <span className="product-list-price-sale" style={{
                  fontSize: isInRecentlyViewed ? '14px' : '16px',
                  padding: isInRecentlyViewed ? '1px 6px' : '2px 8px'
                }}>
                  {Number(product.Discount_price).toLocaleString("vi-VN")}₫
                </span>
                <del className="product-list-price-old" style={{
                  fontSize: isInRecentlyViewed ? '11px' : '13px'
                }}>
                  {Number(product.Price).toLocaleString("vi-VN")}₫
                </del>
              </>
            ) : (
              <span>{Number(product.Price || 0).toLocaleString("vi-VN")}₫</span>
            )}
          </div>
          
          {/* RATING */}
          <div className="product-list-rating" style={{
            fontSize: isInRecentlyViewed ? '12px' : '14px',
            marginBottom: isInRecentlyViewed ? '3px' : '4px'
          }}>
            {Array.from({ length: 5 }).map((_, i) => {
              const rating = ratingInfo.averageRating;
              if (rating >= i + 1) {
                return <span key={i} style={{color:'#FFD700'}}>★</span>;
              } else if (rating > i) {
                return <span key={i} style={{color:'#FFD700'}}>☆</span>;
              } else {
                return <span key={i} style={{color:'#ddd'}}>★</span>;
              }
            })}
            <span style={{
              marginLeft: 4, 
              color: "#888", 
              fontSize: isInRecentlyViewed ? "10px" : "0.95em"
            }}>
              ({ratingInfo.displayText})
            </span>
          </div>
          
          {/* Actions */}
          <div className="product-list-actions" style={{
            marginTop: isInRecentlyViewed ? '4px' : '6px',
            gap: isInRecentlyViewed ? '4px' : '7px',
            flexDirection: isInRecentlyViewed ? 'column' : 'row'
          }}>
            <button
              className="product-list-cart-btn"
              onClick={handleAddToCart}
              disabled={!product.Status || !stockInfo.hasStock}
              style={{
                opacity: (!product.Status || !stockInfo.hasStock) ? 0.6 : 1,
                cursor: (!product.Status || !stockInfo.hasStock) ? 'not-allowed' : 'pointer',
                fontSize: isInRecentlyViewed ? '10px' : '13px',
                padding: isInRecentlyViewed ? '4px 6px' : '5px 10px',
                marginLeft: isInRecentlyViewed ? '0' : '10px',
                width: isInRecentlyViewed ? '100%' : 'auto'
              }}
            >
              {!stockInfo.hasStock
                ? "😔 Hết hàng"
                : stockInfo.hasVariants
                  ? "🎯 Chọn tùy chọn"
                  : "🛒 Thêm vào giỏ hàng"}
            </button>
            
            {/* CHỈ HIỂN THỊ COMPARE NẾU CÓ onAddCompare */}
            {onAddCompare && (
              <button
                className="product-list-compare-btn tooltip-parent"
                onClick={(e) => {
                  e.stopPropagation();
                  if (canAddToCompare) {
                    onAddCompare(product);
                  }
                }}
                disabled={!canAddToCompare}
                style={{
                  opacity: canAddToCompare ? 1 : 0.6,
                  cursor: canAddToCompare ? 'pointer' : 'not-allowed',
                  fontSize: isInRecentlyViewed ? '10px' : '13px',
                  padding: isInRecentlyViewed ? '4px 6px' : '5px 10px',
                  marginLeft: isInRecentlyViewed ? '0' : '10px',
                  width: isInRecentlyViewed ? '100%' : 'auto'
                }}
              >
                {isInCompare ? "Đã chọn" : "So sánh"}
                <span className="tooltip">So sánh sản phẩm</span>
              </button>
            )}
          </div>
        </div>
      </div>

      {/* POPUP CHỌN TÙY CHỌN - GIỐNG PRODUCTLIST */}
      {showVariantPopup && product && (
        <div className="variant-popup-overlay" style={{
          position: "fixed",
          top: 0,
          left: 0,
          right: 0,
          bottom: 0,
          backgroundColor: "rgba(0,0,0,0.5)",
          display: "flex",
          justifyContent: "center",
          alignItems: "center",
          zIndex: 1000,
          marginTop: "50px"
        }}>
          <div className="variant-popup" style={{
            backgroundColor: "white",
            padding: "20px",
            borderRadius: "8px",
            maxWidth: "600px",
            width: "90%"
          }}>
            <h3 style={{ 
              fontSize: "25px",
              fontWeight: "700",
              color: "#000",
            }}>🏸 Lựa chọn phù hợp cho {product.Name}</h3>
            
            {/* Hiển thị tất cả tùy chọn có sẵn */}
            <div style={{ 
              marginBottom: "16px", 
              padding: "8px", 
              backgroundColor: "#f8f9fa", 
              borderRadius: "4px" 
            }}>
              <h4 style={{ 
                margin: "0 0 8px 0", 
                fontSize: "0.9em", 
                color: "#666" 
              }}>
                📦 Các tùy chọn sẵn có ({
                  product.variants?.filter(v => parseInt(v.Quantity || 0, 10) > 0).length || 0
                }/{product.variants?.length || 0}):
              </h4>
              {product.variants?.length > 0 ? (
                <div style={{ fontSize: "0.8em", color: "#555" }}>
                  {product.variants.map((variant, index) => {
                    const quantity = parseInt(variant.Quantity || 0, 10);
                    return (
                      <div key={index} style={{ 
                        display: "flex", 
                        justifyContent: "space-between", 
                        padding: "2px 0",
                        color: quantity > 0 ? "#28a745" : "#dc3545"
                      }}>
                        <span>{variant.Variant_name}</span>
                        <span>{quantity > 0 ? `✅ Còn ${quantity}` : '❌ Hết hàng'}</span>
                      </div>
                    );
                  })}
                </div>
              ) : (
                <span style={{ fontSize: "0.8em", color: "#999" }}>
                  Chưa có tùy chọn nào
                </span>
              )}
            </div>
            
            {/* Render các options nếu có variants */}
            {product.variants?.length > 0 && (
              <>
                {/* Trọng lượng */}
                {getOptionsByPosition(product.variants, 0).length > 0 && (
                  <div style={{ marginBottom: "12px" , display: "flex", gap: "30px", alignItems: "center" }}>
                    <p style={{ marginBottom: "0", fontWeight: "500", minWidth: "120px" }}>
                      ⚖️ Trọng lượng vợt:
                    </p>
                    <div style={{ display: "flex", flexWrap: "wrap", gap: "8px" }}>
                      {getOptionsByPosition(product.variants, 0).map(opt => (
                        <button
                          key={opt}
                          onClick={() => updateSelectedOption('weight', opt)}
                          type="button"
                          style={{
                            padding: "6px 12px",
                            border: "1px solid #ddd",
                            borderRadius: "4px",
                            backgroundColor: selectedOptions.weight === opt ? "#007bff" : "white",
                            color: selectedOptions.weight === opt ? "white" : "#333",
                            cursor: "pointer"
                          }}
                        >
                          {opt}
                        </button>
                      ))}
                    </div>
                  </div>
                )}

                {/* Độ cứng */}
                {getOptionsByPosition(product.variants, 1).length > 0 && (
                  <div style={{ marginBottom: "12px" , display: "flex", gap: "30px", alignItems: "center" }}>
                    <p style={{ marginBottom: "0", fontWeight: "500", minWidth: "120px" }}>
                      🎯 Độ mềm dẻo:
                    </p>
                    <div style={{ display: "flex", flexWrap: "wrap", gap: "8px" }}>
                      {getOptionsByPosition(product.variants, 1).map(opt => (
                        <button
                          key={opt}
                          onClick={() => updateSelectedOption('stiffness', opt)}
                          type="button"
                          style={{
                            padding: "6px 12px",
                            border: "1px solid #ddd",
                            borderRadius: "4px",
                            backgroundColor: selectedOptions.stiffness === opt ? "#007bff" : "white",
                            color: selectedOptions.stiffness === opt ? "white" : "#333",
                            cursor: "pointer"
                          }}
                        >
                          {opt}
                        </button>
                      ))}
                    </div>
                  </div>
                )}

                {/* Điểm cân bằng */}
                {getOptionsByPosition(product.variants, 2).length > 0 && (
                  <div style={{ marginBottom: "12px" , display: "flex", gap: "30px", alignItems: "center" }}>
                    <p style={{ marginBottom: "0", fontWeight: "500", minWidth: "120px" }}>
                      🏸 Điểm cân bằng:
                    </p>
                    <div style={{ display: "flex", flexWrap: "wrap", gap: "8px" }}>
                      {getOptionsByPosition(product.variants, 2).map(opt => (
                        <button
                          key={opt}
                          onClick={() => updateSelectedOption('balance', opt)}
                          type="button"
                          style={{
                            padding: "6px 12px",
                            border: "1px solid #ddd",
                            borderRadius: "4px",
                            backgroundColor: selectedOptions.balance === opt ? "#007bff" : "white",
                            color: selectedOptions.balance === opt ? "white" : "#333",
                            cursor: "pointer"
                          }}
                        >
                          {opt}
                        </button>
                      ))}
                    </div>
                  </div>
                )}

                {/* Lực căng */}
                {getOptionsByPosition(product.variants, 3).length > 0 && (
                  <div style={{ marginBottom: "12px" , display: "flex", gap: "30px", alignItems: "center" }}>
                    <p style={{ marginBottom: "0", fontWeight: "500", minWidth: "120px" }}>
                      🔧 Lực căng dây:
                    </p>
                    <div style={{ display: "flex", flexWrap: "wrap", gap: "8px" }}>
                      {getOptionsByPosition(product.variants, 3).map(opt => (
                        <button
                          key={opt}
                          onClick={() => updateSelectedOption('tension', opt)}
                          type="button"
                          style={{
                            padding: "6px 12px",
                            border: "1px solid #ddd",
                            borderRadius: "4px",
                            backgroundColor: selectedOptions.tension === opt ? "#007bff" : "white",
                            color: selectedOptions.tension === opt ? "white" : "#333",
                            cursor: "pointer"
                          }}
                        >
                          {opt}
                        </button>
                      ))}
                    </div>
                  </div>
                )}

                {/* Lối chơi */}
                {getOptionsByPosition(product.variants, 4).length > 0 && (
                  <div style={{ marginBottom: "12px" , display: "flex", gap: "30px", alignItems: "center" }}>
                    <p style={{ marginBottom: "0", fontWeight: "500", minWidth: "120px" }}>
                      🏆 Phong cách chơi:
                    </p>
                    <div style={{ display: "flex", flexWrap: "wrap", gap: "8px" }}>
                      {getOptionsByPosition(product.variants, 4).map(opt => (
                        <button
                          key={opt}
                          onClick={() => updateSelectedOption('playStyle', opt)}
                          type="button"
                          style={{
                            padding: "6px 12px",
                            border: "1px solid #ddd",
                            borderRadius: "4px",
                            backgroundColor: selectedOptions.playStyle === opt ? "#007bff" : "white",
                            color: selectedOptions.playStyle === opt ? "white" : "#333",
                            cursor: "pointer"
                          }}
                        >
                          {opt}
                        </button>
                      ))}
                    </div>
                  </div>
                )}
              </>
            )}
            
            {/* Hiển thị thông tin tùy chọn đã chọn */}
            {Object.values(selectedOptions).some(option => option && option.trim() !== "") ? (
              selectedVariant ? (
                <div style={{ 
                  margin: "16px 0", 
                  padding: "12px", 
                  backgroundColor: parseInt(selectedVariant.Quantity || 0, 10) > 0 ? "#e8f5e8" : "#fee", 
                  borderRadius: "4px",
                  border: parseInt(selectedVariant.Quantity || 0, 10) > 0 ? "1px solid #28a745" : "1px solid #dc3545"
                }}>
                  <div style={{ 
                    color: parseInt(selectedVariant.Quantity || 0, 10) > 0 ? "#0154b9" : "#dc3545", 
                    fontWeight: "500" 
                  }}>
                    {parseInt(selectedVariant.Quantity || 0, 10) > 0 ? "✨" : "⚠️"} Lựa chọn của bạn: {selectedVariant.Variant_name}
                  </div>
                  <div style={{ marginTop: "8px", fontSize: "20px"}}>
                    💰 Giá: <strong style={{ color: "#d93025" }}>
                      {Number(selectedVariant.Discount_price || selectedVariant.Price || 0).toLocaleString("vi-VN")}₫
                    </strong>
                  </div>
                  <div style={{ 
                    color: parseInt(selectedVariant.Quantity || 0, 10) > 0 ? "#28a745" : "#dc3545",
                    fontWeight: "bold"
                  }}>
                    📦 Số lượng có sẵn: {selectedVariant.Quantity || 0}
                  </div>
                  <div style={{ color: "#666", fontSize: "0.9em" }}>
                    🏷️ Mã sản phẩm: {selectedVariant.SKU || 'N/A'}
                  </div>
                </div>
              ) : (
                <div style={{ 
                  margin: "16px 0", 
                  padding: "12px", 
                  backgroundColor: "#fff3cd", 
                  borderRadius: "4px",
                  border: "1px solid #ffc107",
                  color: "#856404"
                }}>
                  ⚠️ Không tìm thấy tùy chọn phù hợp! Hãy kiểm tra lại các lựa chọn của bạn.
                  <br />
                  <small>Các tùy chọn đã chọn: {Object.entries(selectedOptions)
                    .filter(([key, value]) => value && value.trim() !== "")
                    .map(([key, value]) => `${key}: ${value}`)
                    .join(", ") || "Chưa chọn gì"}</small>
                </div>
              )
            ) : (
              <div style={{ 
                margin: "16px 0", 
                padding: "12px", 
                backgroundColor: "#fff3cd", 
                borderRadius: "4px",
                border: "1px solid #ffc107",
                color: "#856404"
              }}>
                💡 Hãy chọn các thông số phù hợp với nhu cầu của bạn nhé!
              </div>
            )}
            
            {/* Buttons */}
            <div style={{ 
              marginTop: "20px", 
              display: "flex", 
              gap: "10px", 
              justifyContent: "flex-end" 
            }}>
              <button
                disabled={!selectedVariant || parseInt(selectedVariant.Quantity || 0, 10) <= 0}
                onClick={() => {
                  if (!selectedVariant) {
                    alert("Vui lòng chọn tùy chọn phù hợp!");
                    return;
                  }
                  
                  const quantity = parseInt(selectedVariant.Quantity || 0, 10);
                  if (quantity <= 0) {
                    alert("Rất tiếc, tùy chọn này đã hết hàng!");
                    return;
                  }
                  
                  addToCart(product, selectedVariant);
                }}
                style={{
                  padding: "10px 20px",
                  border: "none",
                  borderRadius: "4px",
                  backgroundColor: (!selectedVariant || parseInt(selectedVariant.Quantity || 0, 10) <= 0) 
                    ? "#6c757d" 
                    : "#28a745",
                  color: "white",
                  cursor: (!selectedVariant || parseInt(selectedVariant.Quantity || 0, 10) <= 0) 
                    ? "not-allowed" 
                    : "pointer"
                }}
              >
                🛒 Thêm vào giỏ hàng
              </button>
              <button
                onClick={() => {
                  setShowVariantPopup(false);
                  setSelectedVariant(null);
                  setSelectedOptions({
                    weight: "",
                    stiffness: "",
                    balance: "",
                    tension: "",
                    playStyle: ""
                  });
                }}
                style={{
                  padding: "10px 20px",
                  border: "none",
                  borderRadius: "4px",
                  backgroundColor: "#d93025",
                  color: "#fff",
                  cursor: "pointer"
                }}
              >
                ❌ Đóng
              </button>
            </div>
          </div>
        </div>
      )}
    </>
  );
};

export default ProductCard;
