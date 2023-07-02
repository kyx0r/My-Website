var canvas;
var ctx;
var playerbox_sz = 20;
var cell_sz = 40;
var ballradius;
var player_x;
var player_y;
var last_render = 0
var m_vectors = [];
var cw_cells;
var ch_cells;
var ballx;
var bally;
var score = 0
var scorebox;

function rand(min, max)
{
	return Math.floor(Math.random() * (max - min) + min);
}

/* get random element value in array */
function randarr(arr)
{
	const randomIndex = Math.floor(Math.random() * arr.length);
	const item = arr[randomIndex];
	return item;
}

function draw_player(x, y, size)
{
	ctx.strokeStyle='#FF0000';
	ctx.fillStyle='#FF0000';
	ctx.lineWidth="2";
	ctx.beginPath();
	ctx.rect(x, y, size, size);
	ctx.stroke();
}

/* draws the ball on a given (x , y) */
function draw_point_ball(x, y)
{
	ctx.strokeStyle='#FFCC00';
	ctx.fillStyle='#FFCC00';
	ctx.lineWidth="5";
	ctx.beginPath();
	ctx.arc(x, y, ballradius, 0, 2 * Math.PI, false);
	ctx.fill();
	ctx.stroke();
}

function init_ballpos()
{
	ballradius = rand(2, (cell_sz-5) / 2);
	while (true) {
		ballx = (rand(0, cw_cells) * cell_sz) + cell_sz / 2;
		bally = (rand(0, ch_cells) * cell_sz) + cell_sz / 2;
		var mcellx = Math.floor((ballx * cw_cells) / canvas.width);
		var mcelly = Math.floor((bally * ch_cells) / canvas.height);
		if (!m_vectors[mcelly][mcellx])
			return;
	}
}

function ball_touched()
{
	/* if any of the 4 playerbox corners touched the ball */
	if ((player_x >= ballx - ballradius && player_x <= ballx + ballradius
		&& player_y >= bally - ballradius && player_y <= bally + ballradius)
		|| (player_x + playerbox_sz >= ballx - ballradius && player_x + playerbox_sz <= ballx + ballradius
		&& player_y >= bally - ballradius && player_y <= bally + ballradius)
		|| (player_x + playerbox_sz >= ballx - ballradius && player_x + playerbox_sz <= ballx + ballradius
		&& player_y + playerbox_sz >= bally - ballradius && player_y + playerbox_sz <= bally + ballradius)
		|| (player_x >= ballx - ballradius && player_x <= ballx + ballradius
		&& player_y + playerbox_sz >= bally - ballradius && player_y + playerbox_sz <= bally + ballradius)) {
		score++	
		init_ballpos();
		console.log(score);
	}
	return score
}

function point_counter()
{
	scorebox.innerText = ball_touched();
}

/* return 0 if any bound crossed */
function game_bounds(code)
{
	var change = player_x + player_y;
	switch (code) {
		case "KeyW":
		case "KeyK":
			if (player_y - playerbox_sz < 0)
				player_y = canvas.height;
			break;
		case "KeyS":
		case "KeyJ":
			if (player_y + playerbox_sz >= canvas.height)
				player_y = -playerbox_sz;
			break;
		case "KeyA":
		case "KeyH":
			if (player_x - playerbox_sz < 0)
				player_x = canvas.width;
			break;
		case "KeyD":
		case "KeyL":
			if (player_x + playerbox_sz >= canvas.width)
				player_x = -playerbox_sz;
			break;
	}
	return change == player_x + player_y;
	console.log("%d %d", player_x, player_y);
}

/* return 0 if no collision occurred with the map */
function map_bounds(code)
{
	var result = 0;
	var mcellx = Math.floor((player_x * cw_cells) / canvas.width);
	var mcelly = Math.floor((player_y * ch_cells) / canvas.height);
	var shape = m_vectors[mcelly][mcellx];
	var celly, cellx, nshape;

	switch (code) {
		case "KeyW":
			celly = Math.floor(((player_y - playerbox_sz) * ch_cells) / canvas.height);
			nshape = m_vectors[celly][mcellx];
			if (celly == mcelly)
				break;
			if (shape == 12 || shape == 10 || (shape >= 4 && shape <= 7))
				return -shape;
			if (nshape == 1 || nshape == 3 || nshape == 4 || (nshape >= 7 && nshape <= 9))
				return nshape;
			break;
		case "KeyS":
			celly = Math.floor(((player_y + playerbox_sz) * ch_cells) / canvas.height);
			nshape = m_vectors[celly][mcellx];
			if (celly == mcelly)
				break;
			if (shape == 1 || shape == 3 || shape == 4 || (shape >= 7 && shape <= 9))
				return -shape;
			if (nshape == 12 || nshape == 10 || (nshape >= 4 && nshape <= 7))
				return nshape;
			break;
		case "KeyA":
			cellx = Math.floor(((player_x - playerbox_sz) * cw_cells) / canvas.width);
			nshape = m_vectors[mcelly][cellx];
			if (cellx == mcellx)
				break;
			if (shape == 1 || shape == 2 || shape == 7
				|| shape == 9 || shape == 12 || shape == 10)
				return -shape;
			if (nshape == 1 || nshape == 3 || nshape == 4
				|| nshape == 6 || nshape == 11 || nshape == 10)
				return nshape;
			break;
		case "KeyD":
			cellx = Math.floor(((player_x + playerbox_sz) * cw_cells) / canvas.width);
			nshape = m_vectors[mcelly][cellx];
			if (cellx == mcellx)
				break;
			if (shape == 1 || shape == 3 || shape == 4
				|| shape == 6 || shape == 11 || shape == 10)
				return -shape;
			if (nshape == 1 || nshape == 2 || nshape == 7
				|| nshape == 9 || nshape == 12 || nshape == 10)
				return nshape;
			break;
	}
	return result;
	console.log("cellx: %d celly: %d shape: %d", mcellx, mcelly, shape);
	console.log("ncellx: %d ncelly: %d nshape: %d", cellx, mcelly, nshape);
	console.log("cellx: %d celly: %d shape: %d", mcellx, mcelly, shape);
	console.log("ncellx: %d ncelly: %d nshape: %d", mcellx, celly, nshape);
}

/*
@brief:
draw grid rectangles with one or more walls missing
as specified by the wall param.
*/
function draw_wall(x, y, width, height, wall)
{
	if (wall < 1) // empty cell
		return;
	ctx.beginPath();
	if (wall <= 3) { // top U 1-3
		ctx.moveTo(x, wall != 3 ? y : y + height);
		ctx.lineTo(x, y + height);
		if (wall != 2) {
			ctx.lineTo(x + width, y + height);
			ctx.lineTo(x + width, y);
		}
	} else if (wall <= 6) { // left ] 4-6
		ctx.moveTo(x, y);
		ctx.lineTo(x + width, y);
		if (wall != 5)
			ctx.lineTo(x + width, y + height);
		if (wall == 4)
			ctx.lineTo(x, y + height);
	} else if (wall <= 9) { // right [ 7-9
		ctx.moveTo(x + width, y + height);
		ctx.lineTo(x, y + height);
		if (wall != 8)
			ctx.lineTo(x, y);
		if (wall == 7)
			ctx.lineTo(x + width, y);
	} else if (wall <= 12) { // bottom ^ 10-12
		ctx.moveTo(x + width, wall != 12 ? y + height : y);
		ctx.lineTo(x + width, y);
		if (wall != 11) {
			ctx.lineTo(x, y);
			ctx.lineTo(x, y + height);
		}
	}
	ctx.stroke();
}

/* rationale:
	layout the grids such that ranges have openings
	but do not block each other, ie. 1-3 will never
	block the top wall.
*/
function debug_cells()
{
	//1,4,7,10
	draw_wall(20+cell_sz*1, 25, cell_sz, cell_sz, 1);
	draw_wall(20+cell_sz*3, 25, cell_sz, cell_sz, 4);
	draw_wall(20+cell_sz*5, 25, cell_sz, cell_sz, 7);
	draw_wall(20+cell_sz*7, 25, cell_sz, cell_sz, 10);
	//2,5,8,11
	draw_wall(20+cell_sz*1, 100, cell_sz, cell_sz, 2);
	draw_wall(20+cell_sz*3, 100, cell_sz, cell_sz, 5);
	draw_wall(20+cell_sz*5, 100, cell_sz, cell_sz, 8);
	draw_wall(20+cell_sz*7, 100, cell_sz, cell_sz, 11);
	//3,6,9,12
	draw_wall(20+cell_sz*1, 175, cell_sz, cell_sz, 3);
	draw_wall(20+cell_sz*3, 175, cell_sz, cell_sz, 6);
	draw_wall(20+cell_sz*5, 175, cell_sz, cell_sz, 9);
	draw_wall(20+cell_sz*7, 175, cell_sz, cell_sz, 12);
	return;
}

function draw_mapgrid(w, h)
{
	ctx.strokeStyle='#00CCCC';
	ctx.fillStyle='#FF0000';
	ctx.lineWidth="1";
	var woff = 0;
	var hoff = 0;
	//debug_cells();
	//return;
	for (var y = 0; y < h; y++) {
		for (var x = 0; x < w; x++) {
			ctx.beginPath();
			draw_wall(woff, hoff, cell_sz, cell_sz, m_vectors[y][x]);
			ctx.stroke();
			woff += cell_sz;
		}
		hoff += cell_sz;
		woff = 0;
	}
}

function player_adv(code)
{
	switch (code) {
		case "KeyW":
		case "KeyK":
			player_y = player_y - playerbox_sz;
			break;
		case "KeyS":
		case "KeyJ":
			player_y = player_y + playerbox_sz;
			break;
		case "KeyA":
		case "KeyH":
			player_x = player_x - playerbox_sz;
			break;
		case "KeyD":
		case "KeyL":
			player_x = player_x + playerbox_sz;
			break;
	}
}

function keyev(code)
{
	var result = game_bounds(code);
	if (result && map_bounds(code) != 0)
		return 0;
	player_adv(code);
	return result;
	var mcellx = Math.floor((player_x * cw_cells) / canvas.width);
	var mcelly = Math.floor((player_y * ch_cells) / canvas.height);
	var shape = m_vectors[mcelly][mcellx];
	console.log("cellx: %d celly: %d shape: %d", mcellx, mcelly, shape);
	console.log("x:%d y:%d %s", player_x, player_y, code);
}

function gen_mapgrid(w, h)
{
	for (var y = 0; y < h; y++) {
		m_vectors[y] = [];
		for (var x = 0; x < w; x++)
			m_vectors[y][x] = rand(1, 12);
	}
	/* trace the paths from the center of the map in different directions */
	var res;
	var dirs = [["KeyS", "KeyD"], ["KeyW", "KeyD"], ["KeyS", "KeyA"], ["KeyW", "KeyA"],
			["KeyA"], ["KeyD"], ["KeyW", "KeyD", "KeyA", "KeyS"]];
	for (var idx = 0; idx < 7; idx++) {
		while (true) {
			var mcellx = Math.floor((player_x * cw_cells) / canvas.width);
			var mcelly = Math.floor((player_y * ch_cells) / canvas.height);
			var key = randarr(dirs[idx]);
			if (!game_bounds(key))
				break;
			var shape = map_bounds(key);
			if (shape != 0) {
				//console.log("cellx: %d celly: %d shape: %d", mcellx, mcelly, shape);
				m_vectors[mcelly][mcellx] = 0;
			}
			player_adv(key);
			if (shape != 0) {
				mcellx = Math.floor((player_x * cw_cells) / canvas.width);
				mcelly = Math.floor((player_y * ch_cells) / canvas.height);
				m_vectors[mcelly][mcellx] = 0;
			}
		}
		player_x = canvas.width / 2;
		player_y = canvas.height / 2;
	}
}

document.addEventListener('keydown', (e) => {
	keyev(e.code);
});

function loop(timestamp)
{
	var progress = timestamp - last_render;
	ctx.clearRect(0, 0, canvas.width, canvas.height);
	draw_point_ball(ballx, bally);
	point_counter();
	draw_player(player_x, player_y, playerbox_sz);
	draw_mapgrid(cw_cells, ch_cells);
	last_render = timestamp
	window.requestAnimationFrame(loop)
}

window.onload = function()
{
	canvas = document.getElementById("canvas_game");
	ctx = canvas.getContext("2d");
	draw_player(player_x, player_y, playerbox_sz);
	cw_cells = canvas.width / cell_sz;
	ch_cells = canvas.height / cell_sz;
	player_x = canvas.width / 2;
	player_y = canvas.height / 2;
	gen_mapgrid(cw_cells, ch_cells);
	init_ballpos();
	scorebox = document.getElementById("scorelabel")
	window.requestAnimationFrame(loop)

};
