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

```php
/* 顯示業績表格 + 前五名
 * column 對應 EXCEL 的欄位
 * title 不填為隱藏
 */
[csv_table column="A" title="業績表格"]
```

<img src="https://github.com/j7-dev/mp_csv/assets/9213776/d7caae3e-2715-4762-8b54-30491390a405" />



```php
/* 只顯示前五名
 * column 對應 EXCEL 的欄位
 */
[csv_table_top5 column="A"]
```

![image](https://github.com/j7-dev/mp_csv/assets/9213776/60c8d15d-191c-4c4d-b28b-0c56df60e1c3)



```php
/* 顯示 slider 輪播，會抓取文章縮圖
 * number 預設是 5 篇，可不填
 * post_type 預設是 post，可不填
 */
[csv_swiper]
[csv_swiper number="5" post_type="post"]
```

![image](https://github.com/j7-dev/mp_csv/assets/9213776/df317c5b-09ef-481b-ac20-bc0e288d6437)
