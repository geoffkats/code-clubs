# GitHub Commands Documentation

## 🚀 **Essential Git & GitHub Commands**

This guide covers all the essential Git and GitHub commands you'll need for managing your CodeClub Management System repository.

## 📋 **Basic Git Commands**

### **Repository Setup**
```bash
# Initialize a new Git repository
git init

# Clone an existing repository
git clone https://github.com/geoffkats/code-clubs.git

# Check repository status
git status

# View commit history
git log --oneline
```

### **Configuration**
```bash
# Set your username and email
git config user.name "geoffkats-tech"
git config user.email "katogeoffreyg@gmail.com"

# Set global configuration (for all repositories)
git config --global user.name "geoffkats-tech"
git config --global user.email "katogeoffreyg@gmail.com"

# View current configuration
git config --list
```

## 📝 **Making Changes**

### **Adding Files**
```bash
# Add specific files
git add filename.php
git add resources/views/dashboard.blade.php

# Add all changes
git add .

# Add all files of a specific type
git add *.php
git add resources/views/*.blade.php

# Interactive add (choose what to add)
git add -i
```

### **Committing Changes**
```bash
# Commit with message
git commit -m "Add new attendance tracking feature"

# Commit all staged changes
git commit -am "Update student dashboard with new features"

# Commit with detailed message
git commit -m "Fix attendance grid bug

- Fixed student profile modal not opening
- Updated attendance status colors
- Added bulk edit functionality"
```

## 🔄 **Pushing & Pulling**

### **Pushing to GitHub**
```bash
# Push to main branch
git push origin main

# Push to specific branch
git push origin feature-branch

# Push and set upstream
git push -u origin main

# Force push (use with caution!)
git push --force origin main
```

### **Pulling from GitHub**
```bash
# Pull latest changes
git pull origin main

# Pull specific branch
git pull origin feature-branch

# Pull and rebase
git pull --rebase origin main
```

## 🌿 **Branch Management**

### **Creating & Switching Branches**
```bash
# Create new branch
git branch feature-name

# Create and switch to new branch
git checkout -b feature-name

# Switch to existing branch
git checkout main
git checkout feature-name

# List all branches
git branch -a

# Delete branch
git branch -d feature-name
git branch -D feature-name  # Force delete
```

### **Merging Branches**
```bash
# Merge branch into current branch
git merge feature-name

# Merge with no fast-forward
git merge --no-ff feature-name

# Abort merge if conflicts
git merge --abort
```

## ↩️ **Undoing Changes**

### **Undo Last Commit (Keep Changes)**
```bash
# Undo last commit, keep changes staged
git reset --soft HEAD~1

# Undo last commit, unstage changes
git reset HEAD~1

# Undo last commit, discard all changes
git reset --hard HEAD~1
```

### **Undo Specific Commits**
```bash
# Undo multiple commits (keep changes)
git reset --soft HEAD~3

# Undo to specific commit
git reset --hard abc1234

# Revert a specific commit (creates new commit)
git revert abc1234
```

### **Undo File Changes**
```bash
# Discard changes to specific file
git checkout -- filename.php

# Discard all changes
git checkout -- .

# Unstage file (keep changes)
git reset HEAD filename.php
```

## 🔍 **Viewing & Searching**

### **Viewing History**
```bash
# View commit history
git log

# View compact history
git log --oneline

# View history with file changes
git log --stat

# View history of specific file
git log -- filename.php

# View changes in last commit
git show
```

### **Viewing Differences**
```bash
# View changes in working directory
git diff

# View staged changes
git diff --staged

# View changes between commits
git diff abc1234 def5678

# View changes in specific file
git diff filename.php
```

### **Searching**
```bash
# Search in commit messages
git log --grep="attendance"

# Search in code changes
git log -S "function_name"

# Search in specific file
git log -p -- filename.php
```

## 🏷️ **Tags & Releases**

### **Creating Tags**
```bash
# Create lightweight tag
git tag v1.0.0

# Create annotated tag
git tag -a v1.0.0 -m "Release version 1.0.0"

# Push tags to GitHub
git push origin v1.0.0

# Push all tags
git push origin --tags
```

### **Managing Tags**
```bash
# List tags
git tag

# Delete tag locally
git tag -d v1.0.0

# Delete tag on GitHub
git push origin --delete v1.0.0
```

## 🔧 **Advanced Commands**

### **Stashing Changes**
```bash
# Stash current changes
git stash

# Stash with message
git stash save "Work in progress on attendance feature"

# List stashes
git stash list

# Apply last stash
git stash apply

# Apply specific stash
git stash apply stash@{0}

# Drop stash
git stash drop stash@{0}
```

### **Cherry-picking**
```bash
# Apply specific commit to current branch
git cherry-pick abc1234

# Cherry-pick multiple commits
git cherry-pick abc1234 def5678
```

### **Rebasing**
```bash
# Rebase current branch onto main
git rebase main

# Interactive rebase (last 3 commits)
git rebase -i HEAD~3

# Abort rebase
git rebase --abort
```

## 🚨 **Emergency Commands**

### **Recover Lost Commits**
```bash
# View reflog (recent actions)
git reflog

# Recover lost commit
git checkout abc1234

# Create new branch from recovered commit
git checkout -b recovered-branch abc1234
```

### **Clean Working Directory**
```bash
# Remove untracked files
git clean -f

# Remove untracked files and directories
git clean -fd

# Preview what will be removed
git clean -n
```

## 📊 **Useful Aliases**

Add these to your Git configuration for faster workflow:

```bash
# Set up useful aliases
git config --global alias.st status
git config --global alias.co checkout
git config --global alias.br branch
git config --global alias.ci commit
git config --global alias.unstage 'reset HEAD --'
git config --global alias.last 'log -1 HEAD'
git config --global alias.visual '!gitk'
```

## 🔄 **Common Workflows**

### **Daily Development Workflow**
```bash
# 1. Check status
git status

# 2. Pull latest changes
git pull origin main

# 3. Make changes and stage them
git add .

# 4. Commit changes
git commit -m "Description of changes"

# 5. Push to GitHub
git push origin main
```

### **Feature Development Workflow**
```bash
# 1. Create feature branch
git checkout -b feature/attendance-tracking

# 2. Make changes and commit
git add .
git commit -m "Add attendance tracking feature"

# 3. Push feature branch
git push origin feature/attendance-tracking

# 4. Create Pull Request on GitHub
# 5. After approval, merge to main
git checkout main
git pull origin main
git branch -d feature/attendance-tracking
```

### **Hotfix Workflow**
```bash
# 1. Create hotfix branch from main
git checkout -b hotfix/fix-critical-bug

# 2. Fix the issue and commit
git add .
git commit -m "Fix critical attendance bug"

# 3. Push and create PR
git push origin hotfix/fix-critical-bug

# 4. Merge and cleanup
git checkout main
git pull origin main
git branch -d hotfix/fix-critical-bug
```

## 🛠️ **Troubleshooting**

### **Common Issues & Solutions**

**Issue: "Your branch is ahead of origin/main"**
```bash
# Solution: Push your changes
git push origin main
```

**Issue: "Your branch is behind origin/main"**
```bash
# Solution: Pull latest changes
git pull origin main
```

**Issue: Merge conflicts**
```bash
# 1. Open conflicted files and resolve conflicts
# 2. Stage resolved files
git add filename.php

# 3. Complete merge
git commit
```

**Issue: Accidentally committed to wrong branch**
```bash
# 1. Create new branch from current commit
git checkout -b correct-branch

# 2. Switch back to original branch
git checkout original-branch

# 3. Reset to previous commit
git reset --hard HEAD~1

# 4. Switch to correct branch
git checkout correct-branch
```

## 📚 **GitHub-Specific Commands**

### **Working with Pull Requests**
```bash
# Fetch pull request locally
git fetch origin pull/123/head:pr-123

# Checkout pull request
git checkout pr-123
```

### **Fork Workflow**
```bash
# Add upstream repository
git remote add upstream https://github.com/original/code-clubs.git

# Fetch upstream changes
git fetch upstream

# Merge upstream changes
git merge upstream/main
```

## 🎯 **Best Practices**

1. **Always pull before pushing**
   ```bash
   git pull origin main
   git push origin main
   ```

2. **Write descriptive commit messages**
   ```bash
   git commit -m "Add student profile modal with attendance history"
   ```

3. **Use branches for features**
   ```bash
   git checkout -b feature/new-feature
   ```

4. **Review changes before committing**
   ```bash
   git diff --staged
   ```

5. **Keep commits focused and atomic**
   - One feature per commit
   - One bug fix per commit

---

**Your CodeClub Management System is now on GitHub! 🎉**

Repository: [https://github.com/geoffkats/code-clubs.git](https://github.com/geoffkats/code-clubs.git)

Use these commands to manage your project effectively!
