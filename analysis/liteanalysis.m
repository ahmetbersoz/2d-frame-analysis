%v1.0

clc
clear all


%nodal point coordinates
%[X coordinate, Y coordinate]

% XY=[0.0 0.0; 
%     0.0 3.5; 
%     0.0 6.5; 
%     6.0 6.5;
%     6.0 3.5;
%     6.0 0.0];

XY = csvread('XY.csv',0,1)
XY = XY/100

%material properties

% M=[1 1 1;  %beams
%    2 2 2]; %columns

M = csvread('M.csv',0,1)

%element type and connectivity
%[Start Node, End Node, Material Property]
% C=[1 2 2; 
%    2 3 2;
%    5 4 2;
%    6 5 2;
%    3 4 1; 
%    2 5 1];

C = csvread('C.csv',0,1)

%boundary conditions
%[Nodel point ID, translation in global x direction, translation in global y direction, rotation about global Z axis]
% 0 for free, 1 fore restranined state
% S= [1 1 1 1;
%     6 1 1 1];

S = csvread('S.csv')

%applied nodal point loads
%[nodal point ID, load component in global X,load component in global y, moment component about global Z]
% L= [2 20.0 -30.0 30.0
%     3 30.0 -30.0 30.0;
%     4 0.0 -30.0 -30.0;
%     5 0.0 -30.0 -30.0];

L = csvread('L.csv')
