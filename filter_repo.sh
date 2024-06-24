#!/bin/bash

# Backup the repository first
# git clone --mirror url_of_your_repo.git backup_repo.git

# Navigate to the repository directory
cd url_of_your_repo.git

# List of files to remove
declare -a files=(
"wp-content/uploads/2024/04/GWC-Career-Stories-Shorts-Samuel-Mcculloch-1.mp4"
"wp-content/uploads/2024/04/GWC-Career-Stories-Shorts-Yibniyah-Hawkins-1.mp4"
"wp-content/uploads/2023/11/GWC-Social-Wayne-Video-50.4.mp4"
"wp-content/uploads/2024/06/shutterstock_7252911371.jpg"
"wp-content/uploads/2023/11/GWC-Dusniel-Social-103.mp4"
"wp-content/uploads/2023/11/shutterstock_1549337843.jpg"
"wp-content/uploads/2024/04/GWC-Career-Stories-Shorts-Wendy-Melius-1.mp4"
"wp-content/uploads/2024/04/GWC-Career-Stories-Shorts-Paul-Amos-1.mp4"
"wp-content/uploads/2023/11/GWC-Social-Salina-Video-116.mp4"
"wp-content/uploads/2024/04/GWC-Career-Stories-Shorts-Mike-Stevens-1.mp4"
)

# Remove each file using git filter-repo
for file in "${files[@]}"; do
    git filter-repo --invert-paths --path "$file" --force
done

# Verify the repository and check the log
echo "Files removed. Please verify the integrity and history of your repository."
