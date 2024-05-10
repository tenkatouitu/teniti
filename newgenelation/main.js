// window.addEventListener('DOMContentLoaded', () => {
//     // コンテナを指定
//     const section = document.querySelector('.cherry-blossom-container');
  
//     // 花びらを生成する関数
//     const createPetal = () => {
//       const petalEl = document.createElement('span');
//       petalEl.className = 'petal';
//       const minSize = 15;
//       const maxSize = 25;
//       const size = Math.random() * (maxSize + 1 - minSize) + minSize;
//       petalEl.style.width = `${size}px`;
//       petalEl.style.height = `${size}px`;
//       petalEl.style.left = Math.random() * innerWidth + 'px';
//       section.appendChild(petalEl);
  
//       // 一定時間が経てば花びらを消す
//       setTimeout(() => {
//         petalEl.remove();
//       }, 10000);
//     }
  
//     // 花びらを生成する間隔をミリ秒で指定
//     setInterval(createPetal, 600);
//   });
  window.addEventListener('DOMContentLoaded', ()=> {
    // コンテナを指定
    const container = document.querySelector('.leaves-container');
  
    // 葉っぱを生成する関数
    const createLeaf = (leafClass, minSizeVal, maxSizeVal) => {
      const leafEl = document.createElement('span');
      leafEl.className = `leaf ${leafClass}`;
      const minSize = minSizeVal;
      const maxSize = maxSizeVal;
      const size = Math.random() * (maxSize + 1 - minSize) + minSize;
      leafEl.style.width = `${size}px`;
      leafEl.style.height = `${size}px`;
      leafEl.style.left = Math.random() * 100 + '%';
      container.appendChild(leafEl);
  
      // 一定時間が経てば葉っぱを消す
      setTimeout(() => {
        leafEl.remove();
      }, 180000);
    }
  
    // 葉っぱを生成する間隔をミリ秒で指定する
    // createLeafの引数には、'クラス名', '最小サイズ', '最大サイズ'を渡す
    setInterval(createLeaf.bind(this, 'leaf-1', 30, 80), 2000);
    setInterval(createLeaf.bind(this, 'leaf-2', 30, 80), 2000);
    setInterval(createLeaf.bind(this, 'leaf-3', 30, 80), 2000);
    setInterval(createLeaf.bind(this, 'leaf-4', 30, 80), 2000);
    setInterval(createLeaf.bind(this, 'leaf-5', 30, 80), 2000);
  });
  
