const data = [
  { title: '가게1', postNumber: 1 },
  { title: '가게2', postNumber: 2 },
  { title: '가게3', postNumber: 3 },
  { title: '가게4', postNumber: 4 },
  { title: '가게5', postNumber: 5 },
  { title: '가게6', postNumber: 6 },
  { title: '가게7', postNumber: 7 },
  { title: '가게8', postNumber: 8 },
  { title: '가게9', postNumber: 9 },
  { title: '가게10', postNumber: 10 },
  { title: '가게11', postNumber: 11 },
  { title: '가게12', postNumber: 12 },
  { title: '가게13', postNumber: 13 },
  { title: '가게14', postNumber: 14 },
  { title: '가게15', postNumber: 15 },
  { title: '가게16', postNumber: 16 },
  { title: '가게17', postNumber: 17 },
  { title: '가게18', postNumber: 18 },
  { title: '가게19', postNumber: 19 },
  { title: '가게20', postNumber: 20 },
  { title: '가게21', postNumber: 21 },
  { title: '가게22', postNumber: 22 },
  { title: '가게23', postNumber: 23 },
  { title: '가게24 ', postNumber: 24 },
  { title: '가게25', postNumber: 25 },
  { title: '가게26',postNumber: 26,},
  { title: '가게27', postNumber: 27 }, 
];

const COUNT_PER_PAGE = 6; // 페이지 당 보여줄 게시물 수
const numberButtonWrapper = document.querySelector('.number-button-wrapper'); // 페이지네이션 버튼 wrapper
const ul = document.querySelector('.storelist_ul'); // 게시물을 담을 unordered list
const prevButton = document.querySelector('.prev-button'); // 이전 페이지 버튼
const nextButton = document.querySelector('.next-button'); // 이후 페이지 버튼
let pageNumberButtons; // 페이지 버튼들

let currentPage = 1; // 초기 페이지 번호

// 필요한 페이지 번호 수 구하기
// Math.ceil는 올림함수
const getTotalPageCount = function() {
  return Math.ceil(data.length / COUNT_PER_PAGE);
};

// 페이지 번호 버튼 동적으로 생성하는 함수
const setPageButtons = function() {
  numberButtonWrapper.innerHTML = ''; // 비우기(초기화)

  for (let i = 1; i <= getTotalPageCount(); i++) {
    numberButtonWrapper.innerHTML += `<span class="number-button"> ${i} </span>`;
  }

  numberButtonWrapper.firstChild.classList.add('selected');
  pageNumberButtons = document.querySelectorAll('.number-button');
};

 // 페이지에 해당하는 게시물 ul에 넣어주기
const setPageOf = (pageNumber) => {
  ul.innerHTML = '';

  // ex) pageNumber = 3 이면, 3번째 페이지 보여줘야 하기 때문에 13번째 부터 시작해야함. 6 * ( 3 - 1 ) + 1 = 13 
  for (let i = COUNT_PER_PAGE * (pageNumber - 1) + 1; i <= COUNT_PER_PAGE * (pageNumber - 1) + 6 && i <= data.length; i++) {
    const li = document.createElement('li');

    // 컨테이너
    const postContainer = document.createElement('div');
    postContainer.className = 'post-container';

    // 글 번호
    const postNumber = document.createElement('p');
    postNumber.className = 'post-number';

    // 글 제목
    const postTitle = document.createElement('p');
    postTitle.className = 'post-title';

    postNumber.textContent = data[i - 1].postNumber;
    postTitle.textContent = data[i - 1].title;

    postContainer.append(postNumber, postTitle);
    li.append(postContainer);
    ul.append(li);
  }
};


 // 페이지 이동에 따른 css 
const moveSelectedPageHighlight = function() {
  const pageNumberButtons = document.querySelectorAll('.number-button'); // 페이지 버튼들

  pageNumberButtons.forEach(function(numberButton) {
    if (numberButton.classList.contains('selected')) {
      numberButton.classList.remove('selected');
    }
  });

  pageNumberButtons[currentPage - 1].classList.add('selected');
};

setPageButtons();
setPageOf(currentPage);

// 페이지번호 버튼 클릭 리스너
pageNumberButtons.forEach(function(numberButton)  {
  numberButton.addEventListener('click', function(e) {
    currentPage = +e.target.innerHTML;
    console.log(currentPage);
    setPageOf(currentPage);
    moveSelectedPageHighlight();
  });
});

// 이전 버튼 클릭 리스너
prevButton.addEventListener('click', function() {
  if (currentPage > 1) {
    currentPage -= 1;
    setPageOf(currentPage);
    moveSelectedPageHighlight();
  }
});

// 이후 버튼 클릭 리스너
nextButton.addEventListener('click', function() {
  if (currentPage < getTotalPageCount()) {
    currentPage += 1;
    setPageOf(currentPage);
    moveSelectedPageHighlight();
  }
});