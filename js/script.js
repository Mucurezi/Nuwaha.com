document.addEventListener('DOMContentLoaded', function() {
    // Mobile Menu Toggle
    const menuToggle = document.getElementById('menuToggle');
    const navLinks = document.getElementById('navLinks');

    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            navLinks.classList.toggle('active');
            const icon = menuToggle.querySelector('i');
            if (navLinks.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });

        // Close menu when clicking on a link
        const links = navLinks.querySelectorAll('a');
        links.forEach(link => {
            link.addEventListener('click', function() {
                navLinks.classList.remove('active');
                const icon = menuToggle.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            });
        });
    }

    // Form Handlers
    const admissionForm = document.getElementById('admissionForm');
    const contactForm = document.getElementById('contactForm');
    const loginForm = document.getElementById('loginForm');

    if (admissionForm) {
        // Check if user is logged in first
        fetch('api/check_session.php', {
            method: 'POST'
        })
            .then(response => response.json())
            .then(sessionData => {
                if (!sessionData.logged_in) {
                    // Hide form and show login message
                    const formContainer = admissionForm.closest('.admission-form');
                    if (formContainer) {
                        formContainer.innerHTML = `
                            <div style="background: #f8d7da; padding: 30px; border-radius: 8px; text-align: center; border: 2px solid #f5c6cb;">
                                <i class="fas fa-lock" style="font-size: 48px; color: #721c24; margin-bottom: 20px;"></i>
                                <h2 style="color: #721c24; margin-bottom: 15px;">Login Required</h2>
                                <p style="color: #721c24; margin-bottom: 20px;">You must be logged in to submit an admission application.</p>
                                <a href="register.php" class="cta-btn" style="display: inline-block; margin-right: 10px;">Register Now</a>
                                <a href="login.php" class="cta-btn" style="display: inline-block; background: #555;">Login</a>
                            </div>
                        `;
                    }
                    return;
                }
                
                // User is logged in, show welcome message
                const formContainer = admissionForm.closest('.admission-form');
                const welcomeMsg = document.createElement('div');
                welcomeMsg.style.cssText = 'background: #d4edda; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #28a745;';
                welcomeMsg.innerHTML = `
                    <p style="color: #155724; margin: 0;">
                        <i class="fas fa-user-check"></i> Logged in as: <strong>${sessionData.user.username}</strong> | <a href="#" onclick="event.preventDefault(); if(confirm('Are you sure you want to logout?')) { fetch('api/logout.php', {method: 'POST'}).then(() => window.location.href='login.html'); }" style="color: #155724; text-decoration: underline;">Logout</a>
                    </p>
                `;
                formContainer.insertBefore(welcomeMsg, admissionForm);
            })
            .catch(error => {
                console.error('Session check error:', error);
            });
        
        admissionForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(admissionForm);
            const submitBtn = admissionForm.querySelector('.submit-btn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';
            
            // Debug: Log form data
            console.log('Submitting form data:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }
            
            fetch('api/submit_admission_simple.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.text(); // Get as text first to see raw response
            })
            .then(text => {
                console.log('Raw response:', text);
                try {
                    const data = JSON.parse(text);
                    if (data.success) {
                        alert('✓ ' + data.message + '\n\nSubmitted by: ' + (data.data.username || 'You'));
                        admissionForm.reset();
                    } else {
                        if (data.redirect) {
                            alert('✗ ' + data.message);
                            window.location.href = data.redirect;
                        } else {
                            alert('✗ Error: ' + data.message);
                        }
                    }
                } catch (e) {
                    console.error('JSON parse error:', e);
                    alert('Server error. Check console for details.\n\nResponse: ' + text.substring(0, 200));
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('Network error. Please check:\n1. Is XAMPP running?\n2. Is the path correct?\n3. Check browser console for details.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit Application';
            });
        });
    }

    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(contactForm);
            const submitBtn = contactForm.querySelector('.submit-btn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Sending...';
            
            // Debug: Log form data
            console.log('Submitting contact form:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }
            
            fetch('api/submit_contact.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.text();
            })
            .then(text => {
                console.log('Raw response:', text);
                try {
                    const data = JSON.parse(text);
                    if (data.success) {
                        alert('✓ ' + data.message);
                        contactForm.reset();
                    } else {
                        alert('✗ Error: ' + data.message);
                    }
                } catch (e) {
                    console.error('JSON parse error:', e);
                    alert('Server error. Check console for details.\n\nResponse: ' + text.substring(0, 200));
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('Network error. Please check browser console for details.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Send Message';
            });
        });
    }

    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(loginForm);
            const submitBtn = loginForm.querySelector('.submit-btn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Logging in...';
            
            // Debug: Log form data (don't log password in production!)
            console.log('Attempting login for:', formData.get('username'));
            
            fetch('api/login_simple.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.text();
            })
            .then(text => {
                console.log('Raw response:', text);
                try {
                    const data = JSON.parse(text);
                    if (data.success) {
                        alert('✓ Welcome ' + data.data.username + '!');
                        // Redirect to admission page for parents/students
                        if (data.data.role === 'parent' || data.data.role === 'student') {
                            window.location.href = 'admission.html';
                        } else if (data.data.role === 'admin') {
                            window.location.href = 'dashboard/admin.php';
                        } else if (data.data.role === 'teacher') {
                            window.location.href = 'dashboard/teacher.php';
                        } else {
                            window.location.href = 'index.html';
                        }
                    } else {
                        alert('✗ Error: ' + data.message);
                    }
                } catch (e) {
                    console.error('JSON parse error:', e);
                    alert('Server error. Check console for details.\n\nResponse: ' + text.substring(0, 200));
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('Network error. Please check browser console for details.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Login';
            });
        });
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});

    // Register Form Handler
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(registerForm);
            const submitBtn = registerForm.querySelector('.submit-btn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Creating Account...';
            
            console.log('Registering user...');
            
            fetch('api/register_simple.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.text();
            })
            .then(text => {
                console.log('Raw response:', text);
                try {
                    const data = JSON.parse(text);
                    if (data.success) {
                        alert('✓ ' + data.message);
                        // Redirect to admission page
                        window.location.href = 'admission.html';
                    } else {
                        alert('✗ Error: ' + data.message);
                    }
                } catch (e) {
                    console.error('JSON parse error:', e);
                    alert('Server error. Check console for details.\n\nResponse: ' + text.substring(0, 200));
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('Network error. Please check browser console for details.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Register';
            });
        });
    }

    // Check if user is logged in on admission page
    const admissionPage = document.querySelector('.admission-form');
    if (admissionPage && window.location.pathname.includes('admission.html')) {
        fetch('api/check_session.php', {
            method: 'POST'
        })
            .then(response => response.json())
            .then(data => {
                if (!data.logged_in) {
                    // Show message and redirect to register
                    if (confirm('You need to create an account before applying for admission. Click OK to register now.')) {
                        window.location.href = 'register.php';
                    } else {
                        window.location.href = 'login.php';
                    }
                }
            })
            .catch(error => {
                console.error('Session check error:', error);
            });
    }
});
