import styled, { css } from 'react-emotion';

import { queries } from './mediaQueries';

const Container = styled.section`
	width: 100%;
	height: 100%;
	min-height: 100vh;
	background-color: #fafafa;
`;

export const resultsContainer = css`
	width: calc(100% - 360px);
	margin: 0 10px;

	.list-item {
		margin: 5px 0;
		border: 1px solid #eee;
		padding: 20px;
	}

	${queries.large`
		width: 100%;
	`};
`;

export const dataSearchContainer = css`
	width: 100%;
	max-width: calc(100% - 370px);
	${queries.large`
		margin-left: 10px;
		max-width: calc(100% - 250px);
	`};
	${queries.small`
		margin-left: 0;
		margin-top: 10px;
		max-width: 100%;
	`};
`;

export const appContainer = css`
	margin: 0 auto;
	padding-top: 100px;
	max-width: 1100px;
	${queries.small`
		padding-top: 130px;
	`};
`;

export default Container;
