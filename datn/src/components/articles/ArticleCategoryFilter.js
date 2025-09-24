import React, { useState, useEffect, useRef } from "react";

const ArticleCategoryFilter = ({ categories, selected, onChange }) => {
  const [isOpen, setIsOpen] = useState(false);
  const dropdownRef = useRef(null);

  useEffect(() => {
    const handleClickOutside = (event) => {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
        setIsOpen(false);
      }
    };
    document.addEventListener("mousedown", handleClickOutside);
    return () => document.removeEventListener("mousedown", handleClickOutside);
  }, []);

  const getSelectedCategoryName = () => {
    if (selected === "all") return "Tất cả bài viết";
    if (selected === "hot") return "Bài viết hot nhất";
    if (selected === "newest") return "Bài viết mới nhất";
    if (selected === "most_viewed") return "Bài viết nhiều lượt xem nhất";
    return "Tất cả bài viết";
  };

  const handleCategorySelect = (categoryId) => {
    onChange(categoryId);
    setIsOpen(false);
  };

  return (
    <div ref={dropdownRef} style={{ position: "relative", marginBottom: 32 }}>
      <button
        onClick={() => setIsOpen(!isOpen)}
        style={{
          display: "flex",
          alignItems: "center",
          justifyContent: "space-between",
          gap: 12,
          padding: "16px 24px",
          background: isOpen ? "linear-gradient(135deg, #f0f8ff 0%, #e8f4fd 100%)" : "linear-gradient(135deg, #f6f8fc 0%, #e8f2ff 100%)",
          border: isOpen ? "2px solid #0154b9" : "2px solid #e2e8f0",
          borderRadius: 14,
          cursor: "pointer",
          fontSize: 16,
          fontWeight: 600,
          color: "#0154b9",
          boxShadow: isOpen ? "0 8px 32px rgba(1,84,185,0.15)" : "0 4px 16px rgba(1,84,185,0.08)",
          transition: "all 0.3s ease",
          minWidth: 300,
          maxWidth: 400,
          width: "100%"
        }}
      >
        <span>{getSelectedCategoryName()}</span>
        <span style={{ 
          fontSize: "14px",
          transform: isOpen ? "rotate(180deg)" : "rotate(0deg)",
          transition: "transform 0.3s ease"
        }}>
          ▼
        </span>
      </button>

      {isOpen && (
        <div style={{
          position: "absolute",
          top: "100%",
          left: 0,
          right: 0,
          background: "#ffffff",
          border: "2px solid #e2e8f0",
          borderTop: "1px solid #e2e8f0",
          borderRadius: "0 0 14px 14px",
          boxShadow: "0 12px 40px rgba(1,84,185,0.15)",
          zIndex: 1000,
          maxHeight: 320,
          overflowY: "auto",
          marginTop: "-2px"
        }}>
          <button onClick={() => handleCategorySelect("all")} style={{ width: "100%", padding: "14px 24px", border: "none", background: selected === "all" ? "linear-gradient(90deg, #0154b9, #3bb2ff)" : "transparent", color: selected === "all" ? "#fff" : "#0154b9", textAlign: "left", cursor: "pointer", fontSize: 15, fontWeight: selected === "all" ? 600 : 500, transition: "all 0.2s ease", display: "flex", alignItems: "center", gap: 10, borderBottom: "1px solid #f1f5f9" }}>
            <span>Tất cả bài viết</span>
          </button>
          <button onClick={() => handleCategorySelect("hot")} style={{ width: "100%", padding: "14px 24px", border: "none", background: selected === "hot" ? "linear-gradient(90deg, #0154b9, #3bb2ff)" : "transparent", color: selected === "hot" ? "#fff" : "#0154b9", textAlign: "left", cursor: "pointer", fontSize: 15, fontWeight: selected === "hot" ? 600 : 500, transition: "all 0.2s ease", display: "flex", alignItems: "center", gap: 10, borderBottom: "1px solid #f1f5f9" }}>
            <span>Bài viết hot nhất</span>
          </button>
          <button onClick={() => handleCategorySelect("newest")} style={{ width: "100%", padding: "14px 24px", border: "none", background: selected === "newest" ? "linear-gradient(90deg, #0154b9, #3bb2ff)" : "transparent", color: selected === "newest" ? "#fff" : "#0154b9", textAlign: "left", cursor: "pointer", fontSize: 15, fontWeight: selected === "newest" ? 600 : 500, transition: "all 0.2s ease", display: "flex", alignItems: "center", gap: 10, borderBottom: "1px solid #f1f5f9" }}>
            <span>Bài viết mới nhất</span>
          </button>
          <button onClick={() => handleCategorySelect("most_viewed")} style={{ width: "100%", padding: "14px 24px", border: "none", background: selected === "most_viewed" ? "linear-gradient(90deg, #0154b9, #3bb2ff)" : "transparent", color: selected === "most_viewed" ? "#fff" : "#0154b9", textAlign: "left", cursor: "pointer", fontSize: 15, fontWeight: selected === "most_viewed" ? 600 : 500, transition: "all 0.2s ease", display: "flex", alignItems: "center", gap: 10, borderBottom: "1px solid #f1f5f9" }}>
            <span>Bài viết nhiều lượt xem nhất</span>
          </button>
        </div>
      )}

      {isOpen && (
        <div
          style={{
            position: "fixed",
            top: 0,
            left: 0,
            right: 0,
            bottom: 0,
            zIndex: 999,
            background: "rgba(0,0,0,0.1)"
          }}
          onClick={() => setIsOpen(false)}
        />
      )}
    </div>
  );
};

export default ArticleCategoryFilter;
