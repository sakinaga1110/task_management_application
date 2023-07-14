// モーダルを開く
function openModal() {
  var modal = document.getElementById("myModal");
  modal.style.display = "block";

}

// モーダルの外側をクリックしたら閉じる
window.onclick = function (event) {
  var modal = document.getElementById("myModal");
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

function changeBackgroundColor(id) {
  const container = document.querySelector(".create_Project");

  if (id === "green") {
    container.style.backgroundColor = "green";
  } else if (id === "yellow") {
    container.style.backgroundColor = "yellow";
  } else if (id === "red") {
    container.style.backgroundColor = "red";
  }
}

function toggleForm(event) {
  if (event) {
    event.stopPropagation();
  }

  const taskForm = document.getElementById('taskForm');
  const titleInput = taskForm.querySelector('input[name="title"]');
  const taskDescriptionInput = taskForm.querySelector('textarea[name="task_description"]');

  if (taskForm.style.display === 'none') {
    titleInput.value = '';
    taskDescriptionInput.value = '';
    taskForm.style.backgroundColor = 'white'; // 背景色を白に設定
    taskForm.style.borderRadius = '5px';
    taskForm.style.border = 'solid 2px';
    taskForm.style.paddingTop = '20px';
    taskForm.style.paddingBottom = '20px';
    taskForm.style.paddingLeft = '20px';
  }

  taskForm.style.display = (taskForm.style.display === 'none') ? 'block' : 'none';
};

document.addEventListener("dragstart", (event) => {
  dragged_item = event.target; // ドラッグされた要素を格納
  event.dataTransfer.setData('text/plain', event.target.id);
  //console.log(dragged_item);
  event.target.style.opacity = 0.5;
});


document.addEventListener("dragend", (event) => {
  event.target.style.opacity = 1;
  dragged_item = null; // ドラッグ終了時に初期化
  if (dropTarget) {
    dropTarget.style.border = ''; // ドロップ後は常に実線に設定
  }
});

let dropTarget = null;
let dragged_item = null;

const dropLine = document.createElement('div');
dropLine.classList.add('drop_line');

document.addEventListener("dragover", (event) => {
  event.preventDefault();

  const dropTarget = event.target.closest(".drop_target");
  if (dropTarget) {
    const taskContainer = dropTarget.querySelector('.task-container');
    const taskWrappers = Array.from(taskContainer.querySelectorAll('.task-wrapper'));
    const rect = taskContainer.getBoundingClientRect();
    const mouseY = event.clientY - rect.top;
    dropTarget.style.border = "2px dashed black";
    // ドロップされた位置のインデックスを計算
    let dropIndex = taskWrappers.length;
    for (let i = 0; i < taskWrappers.length; i++) {
      const taskWrapper = taskWrappers[i];
      const wrapperRect = taskWrapper.getBoundingClientRect();
      const wrapperTop = wrapperRect.top - rect.top;
      const wrapperHeight = taskWrapper.clientHeight;
      const wrapperCenter = wrapperTop + wrapperHeight / 2;

      if (mouseY < wrapperCenter) {
        dropIndex = i;
        break;
      }
    }

    // 予測線を表示する位置に移動
    const taskElementBefore = taskWrappers[dropIndex - 1];
    if (taskElementBefore) {
      taskContainer.insertBefore(dropLine, taskElementBefore.nextSibling);
      dropLine.style.borderTop = '4px solid blue';
      dropLine.style.borderBottom = '';


    } else {
      taskContainer.insertBefore(dropLine, taskContainer.firstChild);
      dropLine.style.borderTop = '';
      dropLine.style.borderBottom = '4px solid blue';


    }
  }
});
document.addEventListener("drop", (event) => {
  event.preventDefault();
  dropLine.style.border = '';
  const dropTarget = event.target.closest(".drop_target");
  if (dropTarget) {
    dropTarget.style.border = '';
    const draggedItem = dragged_item; // ドラッグされた要素
    const taskContainer = dropTarget.querySelector('.task-container');
    const taskWrappers = Array.from(taskContainer.querySelectorAll('.task-wrapper'));
    const rect = taskContainer.getBoundingClientRect();
    const mouseY = event.clientY - rect.top;

    // ドロップされた位置のインデックスを計算
    let dropIndex = taskWrappers.length;
    for (let i = 0; i < taskWrappers.length; i++) {
      const taskWrapper = taskWrappers[i];
      const wrapperRect = taskWrapper.getBoundingClientRect();
      const wrapperTop = wrapperRect.top - rect.top;
      const wrapperHeight = taskWrapper.clientHeight;
      const wrapperCenter = wrapperTop + wrapperHeight / 2;

      if (mouseY < wrapperCenter) {
        dropIndex = i;
        break;
      }
    }

    // ドロップされた位置に要素を挿入
    taskContainer.insertBefore(draggedItem, taskWrappers[dropIndex]);

    // ドロップ後のステータスを取得
    const status = dropTarget.id;

    // 順序番号を振り直す
    const taskElements = Array.from(taskContainer.querySelectorAll('.task-wrapper'));

    taskElements.forEach((taskElement, index) => {
      const newOrderNum = index + 1;
      const task_id = taskElement.getAttribute('data-task-id');
      taskElement.setAttribute('data-order-num', newOrderNum);
      updateTask(task_id, status, newOrderNum);
    });

    // 非同期リクエストでデータベースを更新
    taskElements.forEach((taskElement) => {
      const task_id = taskElement.getAttribute('data-task-id');
      const newOrderNum = taskElement.getAttribute('data-order-num');

      const formData = new FormData();
      formData.append('task_id', task_id);
      formData.append('status', status);
      formData.append('order_num', newOrderNum);

      fetch('../Project/task/update.php', {
        method: 'POST',
        body: formData
      })
        .then(response => {
          if (!response.ok) {
            throw new Error('エラーレスポンスを受け取りました');
          }
          return response.json();
        })
        .then(response => {
          console.log("Request sent:", response);
          // 更新が成功した場合の処理

        })
        .catch(error => {
          // エラーハンドリング
          console.error(error);
        });
    });
  }
});




// 非同期リクエストでタスクのステータスと順序番号を更新する関数
function updateTask(task_id, status, order_num) {
  const formData = new FormData();
  formData.append('task_id', task_id);
  formData.append('status', status);
  formData.append('order_num', order_num);
  fetch('../Project/task/update.php', {
    method: 'POST',
    body: formData
  })
    .then(response => {
      if (!response.ok) {
        throw new Error('エラーレスポンスを受け取りました');
      }
      return response.json();
    })
    .then(response => {
      console.log("Request sent:", response);
      // 更新が成功した場合の処理
    })
    .catch(error => {
      // エラーハンドリング
      console.error(error);
    });
}



// ドロップターゲット上にドラッグされた時のスタイルを設定
document.addEventListener("dragenter", (event) => {
  if (event.target.classList.contains("drop_target") && event.target !== dragged_item) {
    dropTarget = event.target;
    dropTarget.style.border = "2px dashed black";
    // 予測線を表示する位置に移動
    const taskContainer = dropTarget.querySelector('.task-container');
    const taskWrappers = Array.from(taskContainer.querySelectorAll('.task-wrapper'));
    const rect = taskContainer.getBoundingClientRect();
    const mouseY = event.clientY - rect.top;

    // ドロップされた位置のインデックスを計算
    let dropIndex = taskWrappers.length;
    for (let i = 0; i < taskWrappers.length; i++) {
      const taskWrapper = taskWrappers[i];
      const wrapperRect = taskWrapper.getBoundingClientRect();
      const wrapperTop = wrapperRect.top - rect.top;
      const wrapperHeight = taskWrapper.clientHeight;
      const wrapperCenter = wrapperTop + wrapperHeight / 2;

      if (mouseY < wrapperCenter) {
        dropIndex = i;
        break;
      }
    }
  }
});
// ドロップターゲットからドラッグが外れた時のスタイルを設定
document.addEventListener("dragleave", (event) => {
  if (event.target.classList.contains("drop_target") && event.target !== dragged_item) {
    event.target.style.border = '';
  }
});
// ajaxで削除
function deleteTask(taskId, taskElement) {
  const formData = new FormData();
  formData.append('id', taskId);

  fetch('../project/task/delete.php', {
    method: 'POST',
    body: formData
  })
    .then(response => response.json())
    .then(data => {
      // レスポンスの処理
      console.log(data);
      if (data.status === 'success') {
        // タスクを非表示にする
        taskElement.style.display = 'none';
      }
    })
    .catch(error => {
      // エラーハンドリング
      console.error(error);
    });
}

document.addEventListener('DOMContentLoaded', () => {
  const taskContainers = document.querySelectorAll('.task-container');

  // 削除ボタンのクリックイベントを設定
  taskContainers.forEach(taskContainer => {
    taskContainer.addEventListener('click', (event) => {
      if (event.target.classList.contains('delete-button')) {
        event.stopPropagation(); // イベントのバブリングを停止

        const button = event.target;
        const taskId = button.dataset.taskId;
        const taskElement = button.closest('.dragged_item');

        if (taskElement) {
          deleteTask(taskId, taskElement);
        }
      }
    });
  });
});

// ajaxで登録
document.addEventListener('DOMContentLoaded', () => {
  const storeButton = document.querySelector('.store-button');
  const taskContainer = document.querySelector('.task-container');
  

  storeButton.addEventListener('click', () => {
    const title = document.querySelector('input[name="title"]').value;
    const taskDescription = document.querySelector('textarea[name="task_description"]').value;
    const orderNum = document.querySelector('input[name="order_num"]').value;
    const status = document.querySelector('input[name="status"]').value;

    // タスク名の入力チェック
    if (!title) {
      alert('タスク名を入力してください');
      return;
    }

    const formData = {
      title: title,
      task_description: taskDescription,
      order_num: orderNum,
      status: status
    };

    const jsonData = JSON.stringify(formData);
    fetch('../project/task/store.php', {
      method: 'POST',
      body: jsonData
    })
      .then(response => response.json())
      .then(data => {
        // レスポンスの処理
        console.log(data);

        // タスクを登録後、フォームを閉じて初期状態に戻す
        toggleForm();

        // タスク要素を作成し、draggable属性を追加
        const taskElement = document.createElement('div');

        taskElement.draggable = true; // draggable属性を追加


        taskElement.innerHTML = `
      <div class="task-wrapper" draggable="true" data-task-id="${data.taskId}" data-order-num="${data.orderNum}">
        <div class="dragged_item">
        <h3>
        <div class="task-title">タイトル：${title}</div>
        <div class="task-description">詳細：${taskDescription}</div>
        </h3>
          <button class="delete-button" data-task-id="${data.taskId}">削除</button>
          </div>
        </div>
        `;

        const deleteButton = taskElement.querySelector('.delete-button');
        deleteButton.addEventListener('click', (event) => {
          const taskId = event.target.dataset.taskId;
          const taskElement = event.target.closest('.task');

          if (taskId && taskElement) {
            deleteTask(taskId, taskElement);
          }
        });
        
    // タスクを一番上に追加
    const firstTask = taskContainer.firstChild;
    taskContainer.insertBefore(taskElement, firstTask);
      })
      .catch(error => {
        // エラーハンドリング
        console.error(error);
      });
  });
});






