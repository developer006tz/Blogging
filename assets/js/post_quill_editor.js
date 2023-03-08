function selectLocalImage() {
  const input = document.createElement("input");
  input.setAttribute("type", "file");
  input.click();

  input.onchange = () => {
    const file = input.files[0];

    if (/^image\//.test(file.type)) { // only accept images
      saveToServer(file);
    } else {
      alert("Only images are allowed.");
    }
  };
}

async function saveToServer(file) {
  const uploadUrl = `${baseUrl}/admin/posts/upload-post-image`;
  const formData = new FormData();
  formData.append("image", file);
  const response = await fetch(uploadUrl, { method: "POST", body: formData });
  const result = await response.json();
  insertToEditor(result.url);
}

const quill = new Quill("#post-editor", {
  modules: {
    toolbar: {
      container: [
        [{ header: [1, 2, 3, 4, false] }],
        ["bold", "italic", "underline"],
        ["link", "image", "blockquote", "code-block"],
        [{ list: "ordered" }, { list: "bullet" }],
      ],
      handlers: {
        image: selectLocalImage,
      },
    },
  },
  placeholder: "",
  theme: "snow",
});

// capture changes in editor as user types
const Delta = Quill.import("delta");
let change = new Delta();
quill.on("text-change", function (delta) {
  change = change.compose(delta);
  const body = document.querySelector(".ql-editor").innerHTML;
  document.querySelector("#post-body").innerHTML = body;
});

function insertToEditor(url) {
  const range = quill.getSelection();
  quill.insertEmbed(range.index, "image", url);
}
