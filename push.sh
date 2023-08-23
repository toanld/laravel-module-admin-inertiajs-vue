#!/bin/bash

# Prompt người dùng nhập phiên bản
read -p "Nhập phiên bản: " version

git add .
git commit -m "update version $version"
git push origin main
# Tạo tag với phiên bản đã nhập
git tag $version
# Đẩy tag lên remote repository
git push origin $version
echo "Đã tạo và đẩy tag thành công với phiên bản $version"
