// 定数
const baseURL = 'journal-search/';  // PHP側へアクセスするためのURL（相対パス可）
const wrapper = '#journal-content';  // 取得された記事を表示する場所（CSSセレクタ）
const max_posts = 3; // 1ページに表示する件数

// 検索実行
document.addEventListener('DOMContentLoaded',()=>{
    const urlSearchParams = new URLSearchParams(window.location.search);
    let params = [];
    let paged = 1; // ページネーションの何ページ目か
    let order = 0; // 0: 降順、1: 昇順
    // paged, order はJS側で用いるので取得、それ以外をクエリに格納
    // 各クエリは上書き（&vol=1&vol=2 みたいな複数指定を想定しない）
    urlSearchParams.forEach((v, k) => {
        if(k == 'paged') {
            paged = parseInt(v);
        } else if(k == 'order') {
            order = v;
        } else if(v !== '') params[k] = v;
    });

    // クエリを結合
    let query = '';
    let first_flag = true;
    for (let [k, v] of Object.entries(params)) {
        if(first_flag) {
            query += '?';
            first_flag = false;
        } else {
            query += '&';
        }
        query += k + '=' + v;
    };
    console.log(query);

    // HTML出力箇所の取得
    const output = document.querySelector(wrapper);
    const div = document.createElement('div');
    const p = document.createElement('p');
    const li = document.createElement('li');
    const ol = document.createElement('ol');
    const a = document.createElement('a');
    
    // データを取得
    fetch(baseURL+query).then(response => {
        return response.json();
    }).then(res => { // PHPから取得したJSONがdataに入っている
        console.log(res);
        if (res.length == 0) {
            p.innerText = '条件に当てはまる記事はありません。';
            output.appendChild(p);
        } else {
            let data = [];
            // ソート用キーを製作
            res.forEach((v, i) => {
                v['order'] = String(v.year).padStart(4, '0') + String(v.vol).padStart(3, '0') + String(v.no).padStart(2, '0') + String(v.pp1).padStart(3, '0') + String(v.pp2).padStart(3, '0');
                if(isFinite(v.order)) data[i] = v;
            });
            // ソート実行
            data.sort((a,b) => {  
                if(order == 0) {
                    if(a.order < b.order) return 1;
                    else if(a.order > b.order) return -1;
                } else {
                    if(a.order < b.order) return -1;
                    else if(a.order > b.order) return 1;
                }
                return 0;
            })
            if (res.length > max_posts) {
                // ページネーション出力
            }
            // 出力
            const dataWrapper = ol.cloneNode();
            dataWrapper.classList.add('journal-items');
            output.appendChild(dataWrapper);
            data.forEach(v => {
                const dataItem = li.cloneNode();
                dataItem.classList.add('item');
                // title_ja
                const data_title_ja = div.cloneNode();
                data_title_ja.classList.add('title_ja');
                data_title_ja.innerText = v.title_ja;
                dataItem.appendChild(data_title_ja);
                // title_en
                const data_title_en = div.cloneNode();
                data_title_en.classList.add('title_en');
                data_title_en.innerText = v.title_en;
                dataItem.appendChild(data_title_en);
                // authors
                const data_authors = div.cloneNode();
                data_authors.classList.add('authors');
                data_authors.innerText = v.authors;
                dataItem.appendChild(data_authors);
                // category
                const data_category = div.cloneNode();
                data_category.classList.add('category');
                data_category.innerText = v.category;
                dataItem.appendChild(data_category);
                // location
                const data_location = div.cloneNode();
                data_location.classList.add('location');
                data_location.innerText = 'Vol. '+v.vol+', No. '+v.no+', '+v.year+' pp.'+v.pp1+'-'+v.pp2;
                dataItem.appendChild(data_location);
                // files
                if(v.pdf != '' && 'pdf' in v) {
                    const data_files = div.cloneNode();
                    data_files.classList.add('files');
                    const pdfs = v.pdf.split(',');
                    const data_files_text = p.cloneNode();
                    data_files_text.innerText = '本文ファイル';
                    data_files.appendChild(data_files_text);
                    pdfs.forEach(pdf => {
                        const link = a.cloneNode();
                        link.setAttribute('href', pdf);
                        link.innerText = pdf.match(".+/(.+?)([\?#;].*)?$")[1];
                        data_files.appendChild(link);
                    });
                    dataItem.appendChild(data_files);
                }
                dataWrapper.appendChild(dataItem);
            });
        }
        return true;
    }).catch(error => {
        console.error(error);
        alert('記事を取得できませんでした。ページをリロードしてください。');
    });
});