# PowerShell script to push Shelluxe project to GitHub
# Run this script after creating your GitHub repository

Write-Host "=== Shelluxe GitHub Push Script ===" -ForegroundColor Cyan
Write-Host ""

# Get GitHub username
$username = Read-Host "Enter your GitHub username (e.g., Kirollos-Samuel)"

# Get repository name
$repoName = Read-Host "Enter repository name (default: Shelluxe)"
if ([string]::IsNullOrWhiteSpace($repoName)) {
    $repoName = "Shelluxe"
}

# Confirm repository URL
$repoUrl = "https://github.com/$username/$repoName.git"
Write-Host ""
Write-Host "Repository URL: $repoUrl" -ForegroundColor Yellow
$confirm = Read-Host "Is this correct? (Y/N)"

if ($confirm -ne "Y" -and $confirm -ne "y") {
    Write-Host "Aborted." -ForegroundColor Red
    exit
}

# Check if remote already exists
$remoteExists = git remote get-url origin 2>$null
if ($remoteExists) {
    Write-Host ""
    Write-Host "Remote 'origin' already exists: $remoteExists" -ForegroundColor Yellow
    $update = Read-Host "Update it? (Y/N)"
    if ($update -eq "Y" -or $update -eq "y") {
        git remote set-url origin $repoUrl
        Write-Host "Remote updated." -ForegroundColor Green
    }
} else {
    git remote add origin $repoUrl
    Write-Host "Remote 'origin' added." -ForegroundColor Green
}

# Check current branch
$currentBranch = git branch --show-current
Write-Host ""
Write-Host "Current branch: $currentBranch" -ForegroundColor Cyan

# Rename to main if on master
if ($currentBranch -eq "master") {
    $rename = Read-Host "Rename branch from 'master' to 'main'? (Y/N)"
    if ($rename -eq "Y" -or $rename -eq "y") {
        git branch -M main
        Write-Host "Branch renamed to 'main'." -ForegroundColor Green
        $currentBranch = "main"
    }
}

# Push to GitHub
Write-Host ""
Write-Host "Pushing to GitHub..." -ForegroundColor Cyan
try {
    git push -u origin $currentBranch
    Write-Host ""
    Write-Host "Success! Your repository has been pushed to GitHub." -ForegroundColor Green
    Write-Host "Visit: https://github.com/$username/$repoName" -ForegroundColor Cyan
} catch {
    Write-Host ""
    Write-Host "Error pushing to GitHub. Make sure:" -ForegroundColor Red
    Write-Host "1. The repository exists on GitHub" -ForegroundColor Yellow
    Write-Host "2. You have proper authentication set up" -ForegroundColor Yellow
    Write-Host "3. You have write access to the repository" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "You can manually run:" -ForegroundColor Cyan
    Write-Host "  git push -u origin $currentBranch" -ForegroundColor White
}

Write-Host ""
Write-Host "Press any key to exit..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")

