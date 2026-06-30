import os

docs_dir = r"c:\laragon\www\project-mi\assets\documents"
os.makedirs(docs_dir, exist_ok=True)

# 1. Write RTF content to .docx files (Word will open RTF files renamed to .docx perfectly)
rtf_pkl = r"""{\rtf1\ansi\deff0
{\fonttbl{\f0\fnil\fcharset0 Arial;}}
\viewkind4\uc1\pard\lang1043\f0\fs28\b TEMPLATE FORMULIR PENDAFTARAN PKL\b0\par
\fs20 Jurusan Manajemen Informatika - Politeknik Negeri Sriwijaya\par
\bar\par
\pard\fs20
Nama Mahasiswa: ...................................................\par
NIM: ...................................................\par
Kelas: ...................................................\par
Perusahaan Tujuan: ...................................................\par
Alamat Perusahaan: ...................................................\par
Tanggal Mulai PKL: ...................................................\par
\par
Palembang, ........................ 2026\par
\par
\par
(...................................................)\par
}"""

rtf_pustaka = r"""{\rtf1\ansi\deff0
{\fonttbl{\f0\fnil\fcharset0 Arial;}}
\viewkind4\uc1\pard\lang1043\f0\fs28\b TEMPLATE BEBAS PUSTAKA JURUSAN\b0\par
\fs20 Jurusan Manajemen Informatika - Politeknik Negeri Sriwijaya\par
\bar\par
\pard\fs20
Dengan ini menerangkan bahwa:\par
Nama Mahasiswa: ...................................................\par
NIM: ...................................................\par
\par
Telah menyelesaikan seluruh pinjaman buku perpustakaan di Jurusan Manajemen Informatika dan dinyatakan bebas pustaka sebagai syarat kelulusan.\par
\par
Palembang, ........................ 2026\par
Kepala Perpustakaan Jurusan,\par
\par
\par
(...................................................)\par
}"""

# 2. Write valid PDF content to panduan_la_2026.pdf
pdf_content = b"""%PDF-1.4
1 0 obj
<< /Type /Catalog /Pages 2 0 R >>
endobj
2 0 obj
<< /Type /Pages /Kids [3 0 R] /Count 1 >>
endobj
3 0 obj
<< /Type /Page /Parent 2 0 R /Resources << /Font << /F1 << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> >> >> /MediaBox [0 0 595 842] /Contents 4 0 R >>
endobj
4 0 obj
<< /Length 195 >>
stream
BT
/F1 18 Tf
70 750 Td
(PANDUAN PENULISAN LAPORAN AKHIR 2026) Tj
/F1 12 Tf
0 -40 Td
(Jurusan Manajemen Informatika - Politeknik Negeri Sriwijaya) Tj
0 -30 Td
(Dokumen ini berisi panduan resmi tata cara penulisan Laporan Akhir D3) Tj
0 -18 Td
(dan Tugas Akhir D4 Manajemen Informatika.) Tj
0 -18 Td
(Harap ikuti format margin, font, dan bab yang telah ditentukan.) Tj
ET
endstream
endobj
xref
0 5
0000000000 65535 f\r
0000000009 00000 n\r
0000000058 00000 n\r
0000000115 00000 n\r
0000000282 00000 n\r
trailer
<< /Size 5 /Root 1 0 R >>
startxref
528
%%EOF
"""

with open(os.path.join(docs_dir, "form_pkl_template.docx"), "w") as f:
    f.write(rtf_pkl)

with open(os.path.join(docs_dir, "bebas_pustaka_mi.docx"), "w") as f:
    f.write(rtf_pustaka)

with open(os.path.join(docs_dir, "panduan_la_2026.pdf"), "wb") as f:
    f.write(pdf_content)

print("Dummy files updated with valid contents!")
