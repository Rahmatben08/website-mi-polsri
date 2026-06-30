import os

workspace = r"c:\laragon\www\project-mi"
keywords = ["kurikulum", "matakuliah", "mata kuliah", "sks"]

for root, dirs, files in os.walk(workspace):
    if ".git" in root or ".gemini" in root or "node_modules" in root or "scratch" in root:
        continue
    for file in files:
        if file.endswith(".php"):
            path = os.path.join(root, file)
            try:
                with open(path, "r", encoding="utf-8", errors="ignore") as f:
                    content = f.read()
                    for kw in keywords:
                        if kw in content.lower():
                            print(f"Found keyword '{kw}' in file: {path}")
                            break
            except Exception as e:
                pass
