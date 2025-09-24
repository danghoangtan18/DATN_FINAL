import React, { useEffect, useState } from "react";
import Header from "../components/home/Header";
import Footer from "../components/home/Footer";
import ArticleList from "../components/articles/ArticleList";
import ArticleSidebar from "../components/articles/ArticleSidebar";
import BreadcrumbNav from "../components/product/BreadcrumbNav";
import SectionHeading from "../components/home/SectionHeading";

// Thêm các component mới
import ArticleCategoryFilter from "../components/articles/ArticleCategoryFilter";
import ArticleSearchBox from "../components/articles/ArticleSearchBox";
import ArticleListSkeleton from "../components/articles/ArticleListSkeleton";
import Pagination from "../components/articles/Pagination";

function ArticleListPage() {
  const [categories, setCategories] = useState([]);
  const [articles, setArticles] = useState([]);
  const [loading, setLoading] = useState(true);
  const [selectedCategory, setSelectedCategory] = useState("all");
  const [search, setSearch] = useState("");
  const [page, setPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);

  // Reset page khi thay đổi filter
  useEffect(() => {
    setPage(1);
  }, [selectedCategory, search]);

  // Lấy danh sách chuyên mục bài viết
  useEffect(() => {
    fetch("/api/post_categories")
      .then(res => res.json())
      .then(data => {
        const categories = data.map(cat => ({
          id: cat.id,
          name: cat.Name
        }));
        setCategories(categories);
      })
      .catch(error => console.error("Error loading categories:", error));
  }, []);

  // Lấy danh sách bài viết theo filter
  useEffect(() => {
    setLoading(true);
    let url = `/api/posts?page=${page}&per_page=12`;
    if (selectedCategory !== "all") url += `&category=${selectedCategory}`;
    if (search) url += `&search=${encodeURIComponent(search)}`;
    
    fetch(url)
      .then(res => res.json())
      .then(data => {
        console.log("DATA POSTS:", data);
        
        if (data.data) {
          // Dữ liệu có phân trang
          const articles = data.data.map(a => ({
            id: a.Post_ID,
            title: a.Title,
            summary: a.Excerpt || a.Summary, 
            content: a.Content,
            thumbnail: a.Thumbnail,
            created_at: a.Created_at,
            view: a.View,
            category: a.category,
            author: a.user,
            ...a
          }));
          setArticles(articles);
          setTotalPages(data.last_page || 1);
        } else {
          // Dữ liệu là mảng đơn giản (fallback)
          const articles = data.map(a => ({
            id: a.Post_ID,
            title: a.Title,
            summary: a.Excerpt || a.Summary,
            ...a
          }));
          setArticles(articles);
          setTotalPages(1);
        }
        setLoading(false);
      })
      .catch((error) => {
        console.error("Error loading articles:", error);
        setLoading(false);
      });
  }, [selectedCategory, search, page]);

  const breadcrumb = [
    { name: "Trang chủ", path: "/" },
    { name: "Bài viết", path: null }
  ];

  return (
    <>
      <Header />
      <BreadcrumbNav items={breadcrumb} />
      <SectionHeading
        title="TẤT CẢ BÀI VIẾT"
        subtitle="Khám phá kiến thức và tin tức mới nhất về cầu lông!"
      />
      <div
        style={{
          maxWidth: 1700,
          margin: "32px auto",
          padding: "0 16px",
          display: "flex",
          gap: 32,
        }}
      >
        
        <main style={{ flex: 3 }}>
          
          {/* Bắt đầu: Bộ lọc và tìm kiếm */}
          <div style={{ display: "flex", gap: 16, marginBottom: 24 }}>
            <ArticleCategoryFilter
              categories={categories}
              selected={selectedCategory}
              onChange={setSelectedCategory}
            />
            <ArticleSearchBox onSearch={setSearch} />
          </div>
          {/* Kết thúc: Bộ lọc và tìm kiếm */}

          {/* Bắt đầu: Danh sách bài viết hoặc skeleton */}
          {loading ? (
            <ArticleListSkeleton />
          ) : (
            <ArticleList articles={articles} />
          )}
          {/* Kết thúc: Danh sách bài viết hoặc skeleton */}

          {/* Bắt đầu: Phân trang */}
          <div style={{ marginTop: 32 }}>
            <Pagination
              page={page}
              totalPages={totalPages}
              onChange={setPage}
            />
          </div>
          {/* Kết thúc: Phân trang */}
        </main>
        <aside style={{ flex: 1 }}>
          <ArticleSidebar />
        </aside>
      </div>
      <Footer />
    </>
  );
}

export default ArticleListPage;