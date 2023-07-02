

function save_game()
{
	localStorage.setItem("player_x" ,player_x);
	localStorage.setItem("player_y", player_y);
	localStorage.setItem("ball_x", ballx);
	localStorage.setItem("ball_y", bally);
	localStorage.setItem("points", score);
	localStorage.setItem("map", m_vectors);
}

function convert_1d_to_2d(arr, num_cols)
{
	const num_rows = Math.ceil(arr.length / num_cols);
	const arr_2d = new Array(num_rows).fill().map(() => new Array(num_cols));
	for (let i = 0; i < arr.length; i++) {
		const row = Math.floor(i / num_cols);
		const col = i % num_cols;
		arr_2d[row][col] = arr[i];
	}
	return arr_2d;
}

function load_game()
{
	player_x = parseInt(localStorage.getItem("player_x"));
	player_y = parseInt(localStorage.getItem("player_y"));
	ballx = parseInt(localStorage.getItem("ball_x"));
	bally = parseInt(localStorage.getItem("ball_y"));
	score =	parseInt(localStorage.getItem("points"));
	var arr1d = localStorage.getItem("map").split(',').map(Number);
	m_vectors = convert_1d_to_2d(arr1d, cw_cells);
}
