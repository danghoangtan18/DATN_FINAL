<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Admin - Vicnex</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Animated background particles */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><circle cx="200" cy="200" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="600" cy="400" r="1" fill="rgba(255,255,255,0.15)"/><circle cx="800" cy="100" r="3" fill="rgba(255,255,255,0.08)"/><circle cx="100" cy="600" r="2" fill="rgba(255,255,255,0.12)"/><circle cx="900" cy="700" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="300" cy="800" r="2" fill="rgba(255,255,255,0.08)"/></svg>');
            animation: float 20s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
            padding: 60px 50px;
            width: 100%;
            max-width: 450px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            animation: slideIn 0.8s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-container {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .login-title {
            color: #4a5568;
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .login-subtitle {
            color: #718096;
            font-size: 1rem;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            color: #4a5568;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .input-wrapper {
            position: relative;
        }

        .form-group input {
            width: 100%;
            padding: 16px 20px 16px 50px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f8fafc;
            color: #2d3748;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 18px;
            transition: color 0.3s ease;
        }

        .form-group input:focus + .input-icon {
            color: #667eea;
        }

        .login-btn {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 18px 20px;
            font-size: 17px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin-top: 10px;
        }

        .login-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.3);
        }

        .login-btn:active {
            transform: translateY(-1px);
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .login-btn:hover::before {
            left: 100%;
        }

        .error-message {
            background: #fed7d7;
            color: #c53030;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #e53e3e;
            font-size: 14px;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 20%, 40%, 60%, 80% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        }

        .footer-text {
            text-align: center;
            color: #718096;
            font-size: 14px;
            margin-top: 30px;
        }

        .back-to-site {
            display: inline-flex;
            align-items: center;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            margin-top: 20px;
            transition: all 0.3s ease;
        }

        .back-to-site:hover {
            color: #764ba2;
            transform: translateX(-5px);
        }

        .back-to-site i {
            margin-right: 8px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-container {
                margin: 20px;
                padding: 40px 30px;
            }
            
            .logo {
                font-size: 2.5rem;
            }
            
            .login-title {
                font-size: 1.5rem;
            }
        }

        /* Loading animation */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        .loading .login-btn {
            background: #a0aec0;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .spinner {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s ease-in-out infinite;
            margin-right: 8px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="logo-container">
            <div class="logo">Vicnex</div>
            <h2 class="login-title">Đăng nhập Admin</h2>
            <p class="login-subtitle">Chào mừng quay trở lại! Vui lòng đăng nhập để tiếp tục.</p>
        </div>

        @if(session('error'))
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        <form id="adminLoginForm" method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <div class="form-group">
                <label for="email">
                    <i class="fas fa-envelope"></i>
                    Địa chỉ Email
                </label>
                <div class="input-wrapper">
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        placeholder="Nhập email admin của bạn"
                        required 
                        autofocus
                        value="{{ old('email') }}"
                    >
                    <i class="fas fa-envelope input-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="password">
                    <i class="fas fa-lock"></i>
                    Mật khẩu
                </label>
                <div class="input-wrapper">
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        placeholder="Nhập mật khẩu của bạn"
                        required
                    >
                    <i class="fas fa-lock input-icon"></i>
                </div>
            </div>

            <button type="submit" class="login-btn">
                <span class="btn-text">Đăng nhập</span>
            </button>
        </form>

        <div class="footer-text">
            <a href="{{ url('/') }}" class="back-to-site">
                <i class="fas fa-arrow-left"></i>
                Quay về trang chủ
            </a>
        </div>
    </div>

    <script>
        // Client-side validation
        function validateForm() {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            let isValid = true;
            
            // Remove previous error styles
            document.querySelectorAll('input').forEach(input => {
                input.style.borderColor = '#e2e8f0';
            });
            
            // Remove existing error messages
            document.querySelectorAll('.field-error').forEach(error => error.remove());
            
            // Email validation
            if (!email) {
                showFieldError('email', 'Email không được để trống');
                isValid = false;
            } else if (!isValidEmail(email)) {
                showFieldError('email', 'Email không hợp lệ');
                isValid = false;
            }
            
            // Password validation
            if (!password) {
                showFieldError('password', 'Mật khẩu không được để trống');
                isValid = false;
            } else if (password.length < 6) {
                showFieldError('password', 'Mật khẩu phải có ít nhất 6 ký tự');
                isValid = false;
            }
            
            return isValid;
        }
        
        function showFieldError(fieldId, message) {
            const field = document.getElementById(fieldId);
            const errorDiv = document.createElement('div');
            
            errorDiv.className = 'field-error';
            errorDiv.style.cssText = `
                color: #e53e3e;
                font-size: 14px;
                margin-top: 5px;
                animation: fadeIn 0.3s ease;
            `;
            errorDiv.textContent = message;
            
            field.style.borderColor = '#e53e3e';
            field.parentElement.appendChild(errorDiv);
        }
        
        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }

        // Add loading state to form submission
        document.getElementById('adminLoginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!validateForm()) {
                return false;
            }
            
            const form = e.target;
            const submitBtn = form.querySelector('.login-btn');
            const btnText = submitBtn.querySelector('.btn-text');
            
            // Add loading state
            form.classList.add('loading');
            btnText.innerHTML = '<span class="spinner"></span>Đang đăng nhập...';
            submitBtn.disabled = true;
            
            // Submit form sau khi validation thành công
            setTimeout(() => form.submit(), 100);
        });

        // Real-time validation
        document.getElementById('email').addEventListener('blur', function() {
            const email = this.value.trim();
            if (email && !isValidEmail(email)) {
                showFieldError('email', 'Email không hợp lệ');
            }
        });
        
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            // Remove error when user starts typing
            const existingError = this.parentElement.querySelector('.field-error');
            if (existingError && password.length > 0) {
                existingError.remove();
                this.style.borderColor = '#e2e8f0';
            }
        });

        // Add input focus animations
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Auto-hide error message after 7 seconds
        const errorMessage = document.querySelector('.error-message');
        if (errorMessage) {
            setTimeout(() => {
                errorMessage.style.opacity = '0';
                errorMessage.style.transform = 'translateY(-20px)';
                setTimeout(() => errorMessage.remove(), 300);
            }, 7000);
        }
        
        // Add fadeIn animation for error messages
        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-10px); }
                to { opacity: 1; transform: translateY(0); }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>