import re

files_to_check = [
    r"c:\laragon\www\project-mi\includes\header.php",
    r"c:\laragon\www\project-mi\about.php",
    r"c:\laragon\www\project-mi\includes\footer.php"
]

for fp in files_to_check:
    print(f"\n=== Lines in {fp} ===")
    with open(fp, "r", encoding="utf-8", errors="ignore") as f:
        for i, line in enumerate(f, 1):
            if any(kw in line.lower() for kw in ["kurikulum", "sks", "matakuliah", "mata kuliah"]):
                print(f"{i}: {line.strip()}")
