# 🚀 Push Project to GitHub - Step by Step Guide

## Prerequisites

1. **Git installed** on your computer
   - Download from: https://git-scm.com/downloads
   - Verify installation: Open terminal/cmd and type `git --version`

2. **GitHub account**
   - Create free account at: https://github.com

---

## Step 1: Prepare Your Project

### A. Create database.php from example
```bash
# Copy the example file
cp config/database.example.php config/database.php
```

Then edit `config/database.php` with your actual database credentials.

### B. Clean up sensitive files
The `.gitignore` file is already created and will prevent sensitive files from being uploaded.

---

## Step 2: Initialize Git Repository

Open terminal/command prompt in your project folder and run:

```bash
# Navigate to your project folder
cd C:\xampp\htdocs\mutunde-parents

# Initialize git repository
git init

# Check status
git status
```

---

## Step 3: Add Files to Git

```bash
# Add all files (respecting .gitignore)
git add .

# Check what will be committed
git status

# Commit the files
git commit -m "Initial commit: School admission system with admin panel"
```

---

## Step 4: Create GitHub Repository

1. Go to https://github.com
2. Click the **"+"** icon (top right) → **"New repository"**
3. Fill in:
   - **Repository name:** `muteesa-school-website` (or your preferred name)
   - **Description:** "School website with online admission system and admin panel"
   - **Visibility:** Choose **Private** (recommended) or Public
   - **DO NOT** check "Initialize with README" (you already have files)
4. Click **"Create repository"**

---

## Step 5: Connect Local to GitHub

GitHub will show you commands. Use these:

```bash
# Add GitHub as remote origin (replace YOUR-USERNAME and REPO-NAME)
git remote add origin https://github.com/YOUR-USERNAME/muteesa-school-website.git

# Verify remote was added
git remote -v

# Push to GitHub
git branch -M main
git push -u origin main
```

**Example:**
```bash
git remote add origin https://github.com/johnsmith/muteesa-school-website.git
git push -u origin main
```

---

## Step 6: Enter GitHub Credentials

When prompted:
- **Username:** Your GitHub username
- **Password:** Use **Personal Access Token** (not your GitHub password)

### How to create Personal Access Token:
1. Go to GitHub → Settings → Developer settings → Personal access tokens → Tokens (classic)
2. Click "Generate new token (classic)"
3. Give it a name: "School Project"
4. Select scopes: Check **"repo"** (full control of private repositories)
5. Click "Generate token"
6. **COPY THE TOKEN** (you won't see it again!)
7. Use this token as your password when pushing

---

## Step 7: Verify Upload

1. Go to your GitHub repository page
2. Refresh the page
3. You should see all your files!

---

## 📁 What Gets Uploaded

✅ **Uploaded:**
- All HTML, PHP, CSS, JS files
- Database schema files (.sql)
- Documentation files (.md)
- Images and assets
- Admin panel files

❌ **NOT Uploaded (Protected by .gitignore):**
- `config/database.php` (sensitive credentials)
- Log files
- Debug files
- Temporary files

---

## 🔄 Future Updates

When you make changes to your project:

```bash
# Check what changed
git status

# Add changed files
git add .

# Commit with message
git commit -m "Description of what you changed"

# Push to GitHub
git push
```

---

## 🛠️ Common Issues & Solutions

### Issue 1: "git: command not found"
**Solution:** Install Git from https://git-scm.com/downloads

### Issue 2: "Permission denied"
**Solution:** Use Personal Access Token instead of password

### Issue 3: "Repository not found"
**Solution:** Check the repository URL is correct

### Issue 4: "Failed to push"
**Solution:** Pull first, then push:
```bash
git pull origin main --allow-unrelated-histories
git push origin main
```

---

## 📱 Using GitHub Desktop (Alternative - Easier)

If you prefer a visual interface:

1. Download **GitHub Desktop**: https://desktop.github.com/
2. Install and login with your GitHub account
3. Click **"Add"** → **"Add existing repository"**
4. Browse to your project folder
5. Click **"Publish repository"**
6. Choose name and visibility
7. Click **"Publish"**

Done! Much easier!

---

## 🎯 Quick Command Summary

```bash
# One-time setup
git init
git add .
git commit -m "Initial commit"
git remote add origin https://github.com/USERNAME/REPO.git
git push -u origin main

# Future updates
git add .
git commit -m "Update message"
git push
```

---

## 🔐 Security Reminder

**NEVER commit these files:**
- ✗ `config/database.php` (contains passwords)
- ✗ `.env` files
- ✗ API keys
- ✗ Private credentials

Always use `.gitignore` to protect sensitive data!

---

## ✅ Checklist

- [ ] Git installed
- [ ] GitHub account created
- [ ] Repository created on GitHub
- [ ] Local repository initialized
- [ ] Files committed
- [ ] Remote origin added
- [ ] Code pushed to GitHub
- [ ] Verified files on GitHub

---

**Need help?** Let me know which step you're stuck on!
