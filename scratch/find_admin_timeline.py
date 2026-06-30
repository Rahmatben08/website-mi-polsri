import sys
import io

# Reconfigure stdout to use utf-8 to prevent cp1252 errors on Windows
if sys.platform == "win32":
    sys.stdout.reconfigure(encoding="utf-8")

with open(r"c:\laragon\www\project-mi\admin\dashboard.php", "r", encoding="utf-8", errors="ignore") as f:
    for i, line in enumerate(f, 1):
        if any(kw in line.lower() for kw in ["aktivitas", "timeline", "stat-widget-num"]):
            print(f"{i}: {line.strip()}")
