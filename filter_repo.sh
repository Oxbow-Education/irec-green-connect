#!/bin/bash

# Backup the repository first
# git clone --mirror url_of_your_repo.git backup_repo.git

# Navigate to the repository directory
# cd backup_repo.git

# Fetch and store the list of large files over 100MB (104857600 bytes)
declare -a files
while IFS= read -r line; do
    files+=("$line")
done < <(git rev-list --objects --all |
    git cat-file --batch-check='%(objecttype) %(objectname) %(objectsize) %(rest)' |
    awk '$1=="blob" && $3 > 104857600 {print $4}' |  # Filters blobs larger than 100MB
    sort -u)  # Removes duplicates

# Check if any large files were found
if [ ${#files[@]} -eq 0 ]; then
    echo "No files larger than 100MB found in the repository."
    exit 0
fi

# Display the files to be removed
echo "The following files will be removed for being larger than 100MB:"
printf '%s\n' "${files[@]}"

# Remove each file using git filter-repo
for file in "${files[@]}"; do
    git filter-repo --invert-paths --path "$file" --force
done

# Verify the repository and check the log
echo "Files removed. Please verify the integrity and history of your repository."

# Optional: Clean up unnecessary files and optimize the local repository
git reflog expire --expire=now --all
git gc --prune=now --aggressive
