# MP CSV 業績表格組件

一句話講完 MP CSV:

> MP CSV 是一個 WordPress 套件，安裝後，可以使用 [csv_table] 的短碼，將 wp-content/demo/ 資料夾內的 csv 檔案還有圖檔，轉換成表格顯示在網頁上。

<br><br><br>

## 注意事項
1. 檔名 & 路徑，皆有固定格式，想自訂請自行修改 `mp_csv.php` 內的屬性。
2. 預設只取 10 欄資料，想要更多請自行修改 `mp_csv.php` 內的屬性。
3. `[csv_table]` 可以輸入 column ，用來對應 `wp-content/demo/wp_live_demo.csv` 的 A 欄資料
4. `[csv_table]` 可以輸入 title ，用來顯示資料標題

## 使用範例
```
[csv_table column="A" title="業績表格"]
```